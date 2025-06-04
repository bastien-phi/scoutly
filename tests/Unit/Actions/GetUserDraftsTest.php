<?php

declare(strict_types=1);

use App\Actions\GetUserDrafts;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Date;

test('action returns draft links sorted by created_at', function () {
    $user = User::factory()->createOne();

    Date::setTestNow();

    $first = Link::factory()->for($user)->draft()->createOne();
    $second = Link::factory()->for($user)->draft()->createOne(['created_at' => now()->subDays(2)]);
    $third = Link::factory()->for($user)->draft()->createOne(['created_at' => now()->subDays(1)]);

    $links = app(GetUserDrafts::class)->execute($user);

    expect($links->collect())
        ->toBeCollection([$first, $third, $second]);
});

it('returns only user\'s links', function () {
    $user = User::factory()->createOne();
    Link::factory(2)->draft()->create();

    $links = app(GetUserDrafts::class)->execute($user);

    expect($links)->toBeEmpty();
});

it('returns only draft links', function () {
    $user = User::factory()->createOne();
    Link::factory()->for($user)->published()->create();

    $links = app(GetUserDrafts::class)->execute($user);

    expect($links)->toBeEmpty();
});
