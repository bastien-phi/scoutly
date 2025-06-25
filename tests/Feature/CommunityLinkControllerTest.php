<?php

declare(strict_types=1);

use App\Actions\GetCommunityLinks;
use App\Data\SearchCommunityLinkFormData;
use App\Models\Link;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns links', function (): void {
        $this->mockAction(GetCommunityLinks::class)
            ->returns(fn () => new LengthAwarePaginator(
                Link::factory(2)->public()->published()->create()->load(['author', 'user', 'tags']),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs(User::factory()->createOne())
            ->get(route('community.links.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('community-links/index')
                    ->has('links.data', 2)
                    ->where('links.data.0.id', $links->first()->id)
                    ->where('links.data.1.id', $links->last()->id)
                    ->where('request', [])
            );
    });

    it('returns links with search', function (): void {
        $this->mockAction(GetCommunityLinks::class)
            ->with(new SearchCommunityLinkFormData(search: 'Hello world', author: 'John Doe', tags: new Collection(['PHP', 'Laravel'])))
            ->returns(fn () => new LengthAwarePaginator(
                Link::factory(2)->create()->load(['author', 'user', 'tags']),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs(User::factory()->createOne())
            ->get(route('community.links.index', [
                'search' => 'Hello world',
                'author' => 'John Doe',
                'tags' => ['PHP', 'Laravel'],
            ]))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('community-links/index')
                    ->has('links.data', 2)
                    ->where('links.data.0.id', $links->first()->id)
                    ->where('links.data.1.id', $links->last()->id)
                    ->where('request', [
                        'author' => 'John Doe',
                        'search' => 'Hello world',
                        'tags' => ['PHP', 'Laravel'],
                    ])
            );
    });

    it('redirects guest to login', function (): void {
        $this->mockAction(GetCommunityLinks::class)
            ->neverCalled();

        $this->get(route('community.links.index'))
            ->assertRedirectToRoute('login');
    });
});
