<?php

declare(strict_types=1);

use App\Actions\GetCommunityLinks;
use App\Data\Requests\GetCommunityLinksRequest;
use App\Models\Link;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns links', function (): void {
        $this->mockAction(GetCommunityLinks::class)
            ->with(new GetCommunityLinksRequest(search: null, author: null, tags: null, user: null))
            ->returns(fn (): LengthAwarePaginator => new LengthAwarePaginator(
                Link::factory(2)->isPublic()->published()->create()->load(['author', 'user', 'tags']),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs(User::factory()->createOne())
            ->get(route('community-links.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('community-links/index')
                    ->has('links.data', 2)
                    ->where('links.data.0.uuid', $links->first()->uuid)
                    ->where('links.data.1.uuid', $links->last()->uuid)
                    ->where('request', [])
                    ->where('user', null)
            );
    });

    it('returns links with search', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(GetCommunityLinks::class)
            ->with(new GetCommunityLinksRequest(search: 'Hello world', author: 'John Doe', tags: ['PHP', 'Laravel'], user: $user->uuid))
            ->returns(fn (): LengthAwarePaginator => new LengthAwarePaginator(
                Link::factory(2)->create()->load(['author', 'user', 'tags']),
                total: 2,
                perPage: 15
            ))
            ->in($links);

        $this->actingAs(User::factory()->createOne())
            ->get(route('community-links.index', [
                'search' => 'Hello world',
                'author' => 'John Doe',
                'tags' => ['PHP', 'Laravel'],
                'user' => $user->uuid,
            ]))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('community-links/index')
                    ->has('links.data', 2)
                    ->where('links.data.0.uuid', $links->first()->uuid)
                    ->where('links.data.1.uuid', $links->last()->uuid)
                    ->where('request', [
                        'author' => 'John Doe',
                        'search' => 'Hello world',
                        'tags' => ['PHP', 'Laravel'],
                        'user' => $user->uuid,
                    ])
                    ->where('user', [
                        'uuid' => $user->uuid,
                        'username' => $user->username,
                    ])
            );
    });

    it('redirects guest to login', function (): void {
        $this->mockAction(GetCommunityLinks::class)
            ->neverCalled();

        $this->get(route('community-links.index'))
            ->assertRedirectToRoute('login');
    });
});
