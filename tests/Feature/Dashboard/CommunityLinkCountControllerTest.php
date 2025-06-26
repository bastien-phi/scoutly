<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;

it('returns the count of community links', function (): void {
    Link::factory(5)
        ->published()
        ->isPublic()
        ->create();

    Link::factory()
        ->published()
        ->isPrivate()
        ->createOne();

    Link::factory()
        ->draft()
        ->isPublic()
        ->createOne();

    $this
        ->actingAs(User::factory()->createOne())
        ->getJson(route('api.dashboard.community-link-count'))
        ->assertOk()
        ->assertData(5);
});
