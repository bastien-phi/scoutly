<?php

declare(strict_types=1);

use App\Actions\GetUserLinks;
use App\Models\Link;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
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

    it('returns not found if user is not allowed to view link guest to login', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->published()->createOne();

        $this->actingAs($user)
            ->get(route('links.show', $link))
            ->assertNotFound();
    });
});
