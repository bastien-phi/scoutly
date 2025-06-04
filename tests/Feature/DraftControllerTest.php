<?php

declare(strict_types=1);

use App\Actions\GetUserDrafts;
use App\Actions\StoreDraft;
use App\Data\DraftFormData;
use App\Models\Link;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
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
            ->assertRedirectToRoute('drafts.index');
    });
});
