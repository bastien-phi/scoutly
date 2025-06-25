<?php

declare(strict_types=1);

use App\Actions\GetUserLinks;
use App\Data\SearchLinkFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

it('returns links sorted by published_at and id', function () {
    $user = User::factory()->createOne();

    Date::setTestNow();

    $first = Link::factory()->for($user)->published(now()->subDay())->createOne();
    $second = Link::factory()->for($user)->published(now()->subDays(2))->createOne();
    $third = Link::factory()->for($user)->published(now()->subDays(2))->createOne();

    $links = app(GetUserLinks::class)->execute(
        $user,
        new SearchLinkFormData(search: null, author_id: null, tags: new Collection)
    );

    expect($links->collect())
        ->toBeCollection([$first, $third, $second]);
});

it('returns only user\'s links', function () {
    $user = User::factory()->createOne();
    Link::factory(2)->create();

    $links = app(GetUserLinks::class)->execute(
        $user,
        new SearchLinkFormData(search: null, author_id: null, tags: new Collection)
    );

    expect($links)->toBeEmpty();
});

it('returns only published links', function () {
    $user = User::factory()->createOne();
    Link::factory()->for($user)->draft()->create();

    $links = app(GetUserLinks::class)->execute(
        $user,
        new SearchLinkFormData(search: null, author_id: null, tags: new Collection)
    );

    expect($links)->toBeEmpty();
});

it('filters by search', function () {
    $user = User::factory()->createOne();

    $first = Link::factory()->for($user)->published()->createOne(['title' => 'Hello World', 'description' => null]);
    $second = Link::factory()->for($user)->published()->createOne([
        'title' => 'High way to hell',
        'description' => null,
    ]);
    $third = Link::factory()->for($user)->published()->createOne(['title' => 'Foo Fighters', 'description' => null]);

    $links = app(GetUserLinks::class)->execute(
        $user,
        new SearchLinkFormData(search: 'Hell', author_id: null, tags: new Collection)
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $second]);
});

it('filters by author', function () {
    $user = User::factory()->createOne();

    $author = Author::factory()->createOne();

    $first = Link::factory()->for($user)->for($author)->published()->createOne();
    $second = Link::factory()->for($user)->published()->createOne();
    $third = Link::factory()->for($user)->for($author)->published()->createOne();

    $links = app(GetUserLinks::class)->execute(
        $user,
        new SearchLinkFormData(search: null, author_id: $author->id, tags: new Collection)
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $third]);
});

it('filters by tags', function () {
    $user = User::factory()->createOne();

    $php = Tag::factory()->for($user)->createOne(['label' => 'PHP']);
    $laravel = Tag::factory()->for($user)->createOne(['label' => 'Laravel']);

    $first = Link::factory()->for($user)->hasAttached($php)->published()->createOne();
    $second = Link::factory()->for($user)->hasAttached([$php, $laravel])->published()->createOne();
    $third = Link::factory()->for($user)->hasAttached($laravel)->published()->createOne();

    $links = app(GetUserLinks::class)->execute(
        $user,
        new SearchLinkFormData(search: null, author_id: null, tags: new Collection([$php->id, $laravel->id]))
    );

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$second]);
});
