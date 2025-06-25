<?php

declare(strict_types=1);

use App\Actions\GetCommunityLinks;
use App\Data\SearchCommunityLinkFormData;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Collection;
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

it('filters by tags', function () {
    $php = Tag::factory()->createOne(['label' => 'PHP']);
    $laravel = Tag::factory()->createOne(['label' => 'Laravel']);

    $first = Link::factory()->hasAttached($php)->published()->public()->createOne();
    $second = Link::factory()->hasAttached([$php, $laravel])->published()->public()->createOne();
    $third = Link::factory()->hasAttached($laravel)->published()->public()->createOne();

    $links = app(GetCommunityLinks::class)->execute(
        new SearchCommunityLinkFormData(search: null, author: null, tags: new Collection(['php', 'laravel']))
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$second]);
});
