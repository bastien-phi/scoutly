<?php

declare(strict_types=1);

use App\Actions\GetUserLinks;
use App\Actions\StoreLink;
use App\Actions\UpdateLink;
use App\Data\Requests\GetUserLinksRequest;
use App\Data\Requests\StoreLinkRequest;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns links', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(GetUserLinks::class)
            ->with($user, new GetUserLinksRequest(search: null, author_uuid: null, tag_uuids: null))
            ->returns(fn (): LengthAwarePaginator => new LengthAwarePaginator(
                Link::factory(2)->for($user)->create()->load(['author', 'tags']),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs($user)
            ->get(route('links.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('links/index')
                    ->has('links.data', 2)
                    ->where('links.data.0.uuid', $links->first()->uuid)
                    ->where('links.data.1.uuid', $links->last()->uuid)
                    ->where('request', [])
            );
    });

    it('returns links with search', function (): void {
        $user = User::factory()->createOne();

        $author = Author::factory()->for($user)->createOne();
        $tag = Tag::factory()->for($user)->createOne();

        $this->mockAction(GetUserLinks::class)
            ->with($user, new GetUserLinksRequest(search: 'Hello world', author_uuid: $author->uuid, tag_uuids: [$tag->uuid]))
            ->returns(fn (): LengthAwarePaginator => new LengthAwarePaginator(
                Link::factory(2)->recycle($user)->create()->load(['author', 'tags']),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs($user)
            ->get(route('links.index', [
                'search' => 'Hello world',
                'author_uuid' => $author->uuid,
                'tag_uuids' => [$tag->uuid],
            ]))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('links/index')
                    ->has('links.data', 2)
                    ->where('links.data.0.uuid', $links->first()->uuid)
                    ->where('links.data.1.uuid', $links->last()->uuid)
                    ->where('request', [
                        'search' => 'Hello world',
                        'author_uuid' => $author->uuid,
                        'tag_uuids' => [$tag->uuid],
                    ])
            );
    });

    it('redirects guest to login', function (): void {
        $this->mockAction(GetUserLinks::class)
            ->neverCalled();

        $this->get(route('links.index'))
            ->assertRedirectToRoute('login');
    });
});

describe('create', function (): void {
    it('shows creation page', function (): void {
        $user = User::factory()->createOne();
        Author::factory()->for($user)->createMany([
            ['name' => 'John Doe'],
            ['name' => 'Jane Smith'],
        ]);

        Tag::factory()->for($user)->createMany([
            ['label' => 'PHP'],
            ['label' => 'Laravel'],
        ]);

        $this->actingAs($user)
            ->get(route('links.create'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('links/create')
                    ->where('authors', fn (Collection $value): bool => $value->all() === ['Jane Smith', 'John Doe'])
                    ->where('tags', fn (Collection $value): bool => $value->all() === ['Laravel', 'PHP'])
            );
    });
});

describe('store', function (): void {
    it('stores the link', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(StoreLink::class)
            ->with(
                $user,
                new StoreLinkRequest(
                    url: 'https://example.com',
                    title: 'Example Title',
                    description: 'Example Description',
                    is_public: true,
                    author: 'John Doe',
                    tags: ['PHP'],
                )
            )
            ->returns(fn () => Link::factory()->for($user)->createOne())
            ->in($link);

        $this->actingAs($user)
            ->post(route('links.store'), [
                'url' => 'https://example.com',
                'title' => 'Example Title',
                'description' => 'Example Description',
                'is_public' => true,
                'author' => 'John Doe',
                'tags' => ['PHP'],
            ])
            ->assertRedirectToRoute('links.show', $link);
    });
});

describe('show', function (): void {
    it('returns link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->published()->createOne();

        $this->actingAs($user)
            ->get(route('links.show', $link))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('links/show')
                    ->has('link')
                    ->where('link.uuid', $link->uuid)
            );
    });

    it('returns not found if user is not allowed to view link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->published()->createOne();

        $this->actingAs($user)
            ->get(route('links.show', $link))
            ->assertNotFound();
    });
});

describe('edit', function (): void {
    it('returns link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()
            ->recycle($user)
            ->published()
            ->forAuthor(['name' => 'John Doe'])
            ->createOne();

        Author::factory()->for($user)->createOne(['name' => 'Jane Smith']);
        Tag::factory()->for($user)->createMany([
            ['label' => 'PHP'],
            ['label' => 'Laravel'],
        ]);

        $this->actingAs($user)
            ->get(route('links.edit', $link))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('links/edit')
                    ->has('link')
                    ->where('link.uuid', $link->uuid)
                    ->where('authors', fn (Collection $value): bool => $value->all() === ['Jane Smith', 'John Doe'])
                    ->where('tags', fn (Collection $value): bool => $value->all() === ['Laravel', 'PHP'])
            );
    });

    it('returns not found if user is not allowed to view link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->published()->createOne();

        $this->actingAs($user)
            ->get(route('links.edit', $link))
            ->assertNotFound();
    });
});

describe('update', function (): void {
    it('updates the link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->published()->createOne();

        $this->mockAction(UpdateLink::class)
            ->with(
                $link,
                new StoreLinkRequest(
                    url: 'https://example.com',
                    title: 'Example Title',
                    description: 'Example Description',
                    is_public: false,
                    author: 'John Doe',
                    tags: ['PHP'],
                )
            );

        $this->actingAs($user)
            ->put(route('links.update', $link), [
                'url' => 'https://example.com',
                'title' => 'Example Title',
                'description' => 'Example Description',
                'is_public' => false,
                'author' => 'John Doe',
                'tags' => ['PHP'],
            ])
            ->assertRedirectToRoute('links.show', $link);
    });
});

describe('destroy', function (): void {
    it('destroys the link and redirects to link index', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->published()->createOne();

        $this->actingAs($user)
            ->delete(route('links.destroy', $link))
            ->assertRedirectToRoute('links.index');

        $this->assertModelMissing($link);
    });

    it('destroy the draft and redirects to drafts index', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->draft()->createOne();

        $this->actingAs($user)
            ->delete(route('links.destroy', $link))
            ->assertRedirectToRoute('drafts.index');

        $this->assertModelMissing($link);
    });
});
