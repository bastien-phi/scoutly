<?php

declare(strict_types=1);

use App\Actions\GetCommunityLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Date;

it('returns links sorted by published_at and id', function () {
    Date::setTestNow();

    $first = Link::factory()->published(now()->subDay())->public()->createOne();
    $second = Link::factory()->published(now()->subDays(2))->public()->createOne();
    $third = Link::factory()->published(now()->subDays(2))->public()->createOne();

    $links = app(GetCommunityLinks::class)->execute();

    expect($links->collect())
        ->toBeCollection([$first, $third, $second]);
});

it('returns only public links', function () {
    Link::factory(2)->private()->create();

    $links = app(GetCommunityLinks::class)->execute();

    expect($links)->toBeEmpty();
});

it('returns only published links', function () {
    Link::factory()->draft()->public()->create();

    $links = app(GetCommunityLinks::class)->execute();

    expect($links)->toBeEmpty();
});
