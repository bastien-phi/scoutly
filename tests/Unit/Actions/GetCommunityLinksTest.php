<?php

declare(strict_types=1);

use App\Actions\GetCommunityLinks;
use App\Data\SearchCommunityLinkFormData;
use App\Models\Link;
use Illuminate\Support\Facades\Date;

it('returns links sorted by published_at and id', function () {
    Date::setTestNow();

    $first = Link::factory()->published(now()->subDay())->public()->createOne();
    $second = Link::factory()->published(now()->subDays(2))->public()->createOne();
    $third = Link::factory()->published(now()->subDays(2))->public()->createOne();

    $links = app(GetCommunityLinks::class)->execute(
        new SearchCommunityLinkFormData(search: null, author: null)
    );

    expect($links->collect())
        ->toBeCollection([$first, $third, $second]);
});

it('returns only public links', function () {
    Link::factory(2)->private()->create();

    $links = app(GetCommunityLinks::class)->execute(
        new SearchCommunityLinkFormData(search: null, author: null)
    );

    expect($links)->toBeEmpty();
});

it('returns only published links', function () {
    Link::factory()->draft()->public()->create();

    $links = app(GetCommunityLinks::class)->execute(
        new SearchCommunityLinkFormData(search: null, author: null)
    );

    expect($links)->toBeEmpty();
});

it('filters by search', function () {
    $first = Link::factory()->published()->public()->createOne(['title' => 'Hello World', 'description' => null]);
    $second = Link::factory()->published()->public()->createOne([
        'title' => 'High way to hell',
        'description' => null,
    ]);
    $third = Link::factory()->published()->public()->createOne(['title' => 'Foo Fighters', 'description' => null]);

    $links = app(GetCommunityLinks::class)->execute(
        new SearchCommunityLinkFormData(search: 'Hell', author: null)
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $second]);
});

it('filters by author', function () {
    $first = Link::factory()
        ->published()
        ->public()
        ->forAuthor(['name' => 'John Doe'])
        ->createOne();
    $second = Link::factory()
        ->published()
        ->public()
        ->forAuthor(['name' => 'John DOE'])
        ->createOne();
    $third = Link::factory()
        ->published()
        ->public()
        ->forAuthor(['name' => 'Jane Doe'])
        ->createOne();

    $links = app(GetCommunityLinks::class)->execute(
        new SearchCommunityLinkFormData(search: null, author: 'John DOE')
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $second]);
});
