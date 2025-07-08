<?php

declare(strict_types=1);

use App\Actions\GetCommunityUsers;
use App\Models\Link;
use App\Models\User;

it('gets community users', function (): void {
    $john = User::factory()
        ->has(Link::factory()->isPublic()->published())
        ->createOne(['username' => 'john']);
    $jane = User::factory()
        ->has(Link::factory()->isPublic()->published())
        ->createOne(['username' => 'jane']);

    User::factory()
        ->has(Link::factory()->isPublic()->draft())
        ->createOne();

    User::factory()
        ->has(Link::factory()->isPrivate()->published())
        ->createOne();

    $user = app(GetCommunityUsers::class)->execute(search: null);

    expect($user)->toBeCollection([$jane, $john]);
});

it('limits results to 30', function (): void {
    User::factory(31)
        ->has(Link::factory()->isPublic()->published())
        ->create();

    $user = app(GetCommunityUsers::class)->execute(search: null);

    expect($user)->toHaveCount(30);
});

it('filters users', function (): void {
    $john = User::factory()
        ->has(Link::factory()->isPublic()->published())
        ->createOne(['username' => 'John Doe']);
    $jane = User::factory()
        ->has(Link::factory()->isPublic()->published())
        ->createOne(['username' => 'Jane Doe']);
    $patrick = User::factory()
        ->has(Link::factory()->isPublic()->published())
        ->createOne(['username' => 'Patrick Smith']);

    $user = app(GetCommunityUsers::class)->execute(search: 'do');

    expect($user)->toBeCollection([$jane, $john]);
});
