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
