<?php

declare(strict_types=1);

use App\Actions\GetUserLinks;
use App\Actions\StoreLink;
use App\Actions\UpdateLink;
use App\Data\LinkFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns links', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(GetUserLinks::class)
            ->with($user)
            ->returns(fn () => new LengthAwarePaginator(
                Link::factory(2)->for($user)->create(),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs($user)
            ->get(route('links.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('links/index')
                    ->has('links.data', 2)
                    ->where('links.data.0.id', $links->first()->id)
                    ->where('links.data.1.id', $links->last()->id)
            );
    });

    it('redirects guest to login', function (): void {
        $this->mockAction(GetUserLinks::class)
            ->neverCalled();

        $this->get(route('links.index'))
            ->assertRedirectToRoute('login');
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
                fn (AssertableInertia $page) => $page
                    ->component('links/show')
                    ->has('link')
                    ->where('link.id', $link->id)
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

describe('create', function (): void {
    it('shows creation page', function (): void {
        $user = User::factory()->createOne();
        Author::factory()->createMany([
            ['name' => 'John Doe'],
            ['name' => 'Jane Smith'],
        ]);

        $this->actingAs($user)
            ->get(route('links.create'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('links/create')
                    ->where('authors', fn (Collection $value) => $value->all() === ['Jane Smith', 'John Doe'])
            );
    });
});

describe('store', function (): void {
    it('stores the link', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(StoreLink::class)
            ->with(
                $user,
                new LinkFormData(
                    url: 'https://example.com',
                    title: 'Example Title',
                    description: 'Example Description',
                    author: 'John Doe',
                )
            )
            ->returns(fn () => Link::factory()->for($user)->createOne())
            ->in($link);

        $this->actingAs($user)
            ->post(route('links.store'), [
                'url' => 'https://example.com',
                'title' => 'Example Title',
                'description' => 'Example Description',
                'author' => 'John Doe',
            ])
            ->assertRedirectToRoute('links.show', $link);
    });
});

describe('edit', function (): void {
    it('returns link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)
            ->published()
            ->forAuthor(['name' => 'John Doe'])
            ->createOne();

        Author::factory()->createOne(['name' => 'Jane Smith']);

        $this->actingAs($user)
            ->get(route('links.edit', $link))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('links/edit')
                    ->has('link')
                    ->where('link.id', $link->id)
                    ->where('authors', fn (Collection $value) => $value->all() === ['Jane Smith', 'John Doe'])
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
                new LinkFormData(
                    url: 'https://example.com',
                    title: 'Example Title',
                    description: 'Example Description',
                    author: 'John Doe',
                )
            );

        $this->actingAs($user)
            ->put(route('links.update', $link), [
                'url' => 'https://example.com',
                'title' => 'Example Title',
                'description' => 'Example Description',
                'author' => 'John Doe',
            ])
            ->assertRedirectToRoute('links.show', $link);
    });
});

describe('destroy', function (): void {
    it('destroy the link and redirects to link index', function (): void {
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
