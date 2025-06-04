<?php

declare(strict_types=1);

use App\Actions\GetUserDrafts;
use App\Actions\StoreDraft;
use App\Actions\UpdateDraft;
use App\Data\DraftFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns draft links', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(GetUserDrafts::class)
            ->with($user)
            ->returns(fn () => new LengthAwarePaginator(
                Link::factory(2)->for($user)->create(),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs($user)
            ->get(route('drafts.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('drafts/index')
                    ->has('drafts.data', 2)
                    ->where('drafts.data.0.id', $links->first()->id)
                    ->where('drafts.data.1.id', $links->last()->id)
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
                    author: null,
                )
            )
            ->returns(fn () => Link::factory()->for($user)->draft()->createOne())
            ->in($draft);

        $this->actingAs($user)
            ->post(route('drafts.store'), [
                'url' => 'https://example.com',
                'title' => null,
                'description' => null,
                'author' => null,
            ])
            ->assertRedirectToRoute('drafts.edit', $draft);
    });
});

describe('edit', function (): void {
    it('returns draft link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)
            ->draft()
            ->forAuthor(['name' => 'John Doe'])
            ->createOne();

        Author::factory()->createOne(['name' => 'Jane Smith']);

        $this->actingAs($user)
            ->get(route('drafts.edit', $link))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('drafts/edit')
                    ->has('draft')
                    ->where('draft.id', $link->id)
                    ->where('authors', fn (Collection $value) => $value->all() === ['Jane Smith', 'John Doe'])
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

        $this->mockAction(UpdateDraft::class)
            ->with(
                $link,
                new DraftFormData(
                    url: 'https://example.com',
                    title: 'Example Title',
                    description: 'Example Description',
                    author: 'John Doe',
                )
            );

        $this->actingAs($user)
            ->put(route('drafts.update', $link), [
                'url' => 'https://example.com',
                'title' => 'Example Title',
                'description' => 'Example Description',
                'author' => 'John Doe',
            ])
            ->assertRedirectToRoute('drafts.edit', $link);
    });
});
