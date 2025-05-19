<?php

declare(strict_types=1);

use App\Actions\GetUserLinks;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Date;

test('action returns links sorted by published_at and created_at', function () {
    $user = User::factory()->createOne();

    Date::setTestNow();

    $first = Link::factory()->for($user)->published(now()->subDay())->createOne();
    $second = Link::factory()->for($user)->published(now()->subDays(2))->createOne(['created_at' => now()->subDays(2)]);
    $third = Link::factory()->for($user)->published(now()->subDays(2))->createOne(['created_at' => now()->subDays(1)]);

    $links = app(GetUserLinks::class)->execute($user);

    expect($links->collect())
        ->toBeCollection([$first, $third, $second]);
});

it('returns only user\'s links', function () {
    $user = User::factory()->createOne();
    Link::factory(2)->create();

    $links = app(GetUserLinks::class)->execute($user);

    expect($links)->toBeEmpty();
});

it('returns only published links', function () {
    $user = User::factory()->createOne();
    Link::factory()->for($user)->draft()->create();

    $links = app(GetUserLinks::class)->execute($user);

    expect($links)->toBeEmpty();
});
