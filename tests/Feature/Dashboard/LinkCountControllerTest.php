<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;

it('returns the count of user links', function (): void {
    $user = User::factory()->createOne();

    Link::factory(3)
        ->for($user)
        ->published()
        ->create();

    Link::factory()
        ->for($user)
        ->draft()
        ->createOne();

    $this
        ->actingAs($user)
        ->getJson(route('api.dashboard.link-count'))
        ->assertOk()
        ->assertData(3);
});
