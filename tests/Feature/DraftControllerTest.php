<?php

declare(strict_types=1);

use App\Actions\GetUserDrafts;
use App\Actions\StoreDraft;
use App\Actions\UpdateLink;
use App\Data\DraftFormData;
use App\Data\ToastData;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns draft links', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(GetUserDrafts::class)
            ->with($user)
            ->returns(fn (): LengthAwarePaginator => new LengthAwarePaginator(
                Link::factory(2)->for($user)->create()->load(['author', 'tags']),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs($user)
            ->get(route('drafts.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('drafts/index')
                    ->has('drafts.data', 2)
                    ->where('drafts.data.0.uuid', $links->first()->uuid)
                    ->where('drafts.data.1.uuid', $links->last()->uuid)
            );
    });
});

describe('store', function (): void {
    it('stores the draft', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(StoreDraft::class)
            ->with(
                $user,
                new DraftFormData(
                    url: 'https://example.com',
                    title: null,
                    description: null,
                    is_public: false,
                    author: null,
                    tags: new Collection,
                )
            )
            ->returns(fn () => Link::factory()->for($user)->draft()->createOne())
            ->in($draft);

        $this->actingAs($user)
            ->post(route('drafts.store'), [
                'url' => 'https://example.com',
                'title' => null,
                'description' => null,
                'is_public' => false,
                'author' => null,
                'tags' => [],
            ])
            ->assertRedirectToRoute('drafts.edit', $draft)
            ->assertSessionHas(
                'toast',
                ToastData::success('Draft saved')
            );
    });
});

describe('edit', function (): void {
    it('returns draft link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()
            ->recycle($user)
            ->draft()
            ->forAuthor(['name' => 'John Doe'])
            ->createOne();

        Author::factory()->for($user)->createOne(['name' => 'Jane Smith']);
        Tag::factory()->for($user)->createMany([
            ['label' => 'PHP'],
            ['label' => 'Laravel'],
        ]);

        $this->actingAs($user)
            ->get(route('drafts.edit', $link))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('drafts/edit')
                    ->has('draft')
                    ->where('draft.uuid', $link->uuid)
                    ->where('authors', fn (Collection $value): bool => $value->all() === ['Jane Smith', 'John Doe'])
                    ->where('tags', fn (Collection $value): bool => $value->all() === ['Laravel', 'PHP'])
            );
    });

    it('returns not found if user is not allowed to view link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->draft()->createOne();

        $this->actingAs($user)
            ->get(route('drafts.edit', $link))
            ->assertNotFound();
    });
});

describe('update', function (): void {
    it('updates the draft link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->draft()->createOne();

        $this->mockAction(UpdateLink::class)
            ->with(
                $link,
                new DraftFormData(
                    url: 'https://example.com',
                    title: 'Example Title',
                    description: 'Example Description',
                    is_public: true,
                    author: 'John Doe',
                    tags: new Collection(['PHP'])
                )
            );

        $this->actingAs($user)
            ->put(route('drafts.update', $link), [
                'url' => 'https://example.com',
                'title' => 'Example Title',
                'description' => 'Example Description',
                'is_public' => true,
                'author' => 'John Doe',
                'tags' => ['PHP'],
            ])
            ->assertRedirectToRoute('drafts.edit', $link)
            ->assertSessionHas(
                'toast',
                ToastData::success('Draft saved')
            );
    });
});
