<?php

declare(strict_types=1);

use App\Actions\GetCommunityLinks;
use App\Data\Requests\GetCommunityLinksRequest;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Date;

it('returns links sorted by published_at and id', function (): void {
    Date::setTestNow();

    $first = Link::factory()->published(now()->subDay())->isPublic()->createOne();
    $second = Link::factory()->published(now()->subDays(2))->isPublic()->createOne();
    $third = Link::factory()->published(now()->subDays(2))->isPublic()->createOne();

    $links = app(GetCommunityLinks::class)->execute(
        new GetCommunityLinksRequest(search: null, author: null, tags: null, user: null)
    );

    expect($links->collect())
        ->toBeCollection([$first, $third, $second]);
});

it('returns only public links', function (): void {
    Link::factory(2)->isPrivate()->create();

    $links = app(GetCommunityLinks::class)->execute(
        new GetCommunityLinksRequest(search: null, author: null, tags: null, user: null)
    );

    expect($links)->toBeEmpty();
});

it('returns only published links', function (): void {
    Link::factory()->draft()->isPublic()->create();

    $links = app(GetCommunityLinks::class)->execute(
        new GetCommunityLinksRequest(search: null, author: null, tags: null, user: null)
    );

    expect($links)->toBeEmpty();
});

it('filters by search', function (): void {
    $first = Link::factory()->published()->isPublic()->createOne(['title' => 'Hello World', 'description' => null]);
    $second = Link::factory()->published()->isPublic()->createOne([
        'title' => 'High way to hell',
        'description' => null,
    ]);
    $third = Link::factory()->published()->isPublic()->createOne(['title' => 'Foo Fighters', 'description' => null]);

    $links = app(GetCommunityLinks::class)->execute(
        new GetCommunityLinksRequest(search: 'Hell', author: null, tags: null, user: null)
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $second]);
});

it('filters by author', function (): void {
    $first = Link::factory()
        ->published()
        ->isPublic()
        ->forAuthor(['name' => 'John Doe'])
        ->createOne();
    $second = Link::factory()
        ->published()
        ->isPublic()
        ->forAuthor(['name' => 'John DOE'])
        ->createOne();
    $third = Link::factory()
        ->published()
        ->isPublic()
        ->forAuthor(['name' => 'Jane Doe'])
        ->createOne();

    $links = app(GetCommunityLinks::class)->execute(
        new GetCommunityLinksRequest(search: null, author: 'John DOE', tags: null, user: null)
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $second]);
});

it('filters by tags', function (): void {
    $php = Tag::factory()->createOne(['label' => 'PHP']);
    $laravel = Tag::factory()->createOne(['label' => 'Laravel']);

    $first = Link::factory()->hasAttached($php)->published()->isPublic()->createOne();
    $second = Link::factory()->hasAttached([$php, $laravel])->published()->isPublic()->createOne();
    $third = Link::factory()->hasAttached($laravel)->published()->isPublic()->createOne();

    $links = app(GetCommunityLinks::class)->execute(
        new GetCommunityLinksRequest(search: null, author: null, tags: ['php', 'laravel'], user: null)
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$second]);
});

it('filters by user', function (): void {
    $john = User::factory()->createOne();
    $jane = User::factory()->createOne();

    $first = Link::factory()->for($jane)->published()->isPublic()->createOne();
    $second = Link::factory()->for($john)->published()->isPublic()->createOne();
    $third = Link::factory()->published()->isPublic()->createOne();

    $links = app(GetCommunityLinks::class)->execute(
        new GetCommunityLinksRequest(search: null, author: null, tags: null, user: $john->uuid)
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$second]);
});
