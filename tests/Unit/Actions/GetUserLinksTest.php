<?php

declare(strict_types=1);

use App\Actions\GetUserLinks;
use App\Data\SearchLinkFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Date;

it('returns links sorted by published_at and created_at', function () {
    $user = User::factory()->createOne();

    Date::setTestNow();

    $first = Link::factory()->for($user)->published(now()->subDay())->createOne();
    $second = Link::factory()->for($user)->published(now()->subDays(2))->createOne(['created_at' => now()->subDays(2)]);
    $third = Link::factory()->for($user)->published(now()->subDays(2))->createOne(['created_at' => now()->subDays(1)]);

    $links = app(GetUserLinks::class)->execute($user, new SearchLinkFormData(search: null, author_id: null));

    expect($links->collect())
        ->toBeCollection([$first, $third, $second]);
});

it('returns only user\'s links', function () {
    $user = User::factory()->createOne();
    Link::factory(2)->create();

    $links = app(GetUserLinks::class)->execute($user, new SearchLinkFormData(search: null, author_id: null));

    expect($links)->toBeEmpty();
});

it('returns only published links', function () {
    $user = User::factory()->createOne();
    Link::factory()->for($user)->draft()->create();

    $links = app(GetUserLinks::class)->execute($user, new SearchLinkFormData(search: null, author_id: null));

    expect($links)->toBeEmpty();
});

it('filters by search', function () {
    $user = User::factory()->createOne();

    Date::setTestNow();

    $first = Link::factory()->for($user)->published()->createOne(['title' => 'Hello World', 'description' => null]);
    $second = Link::factory()->for($user)->published()->createOne(['title' => 'High way to hell', 'description' => null]);
    $third = Link::factory()->for($user)->published()->createOne(['title' => 'Foo Fighters', 'description' => null]);

    $links = app(GetUserLinks::class)->execute($user, new SearchLinkFormData(search: 'Hell', author_id: null));

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $second]);
});

it('filters by author', function () {
    $user = User::factory()->createOne();

    Date::setTestNow();

    $author = Author::factory()->createOne();

    $first = Link::factory()->for($user)->for($author)->published()->createOne();
    $second = Link::factory()->for($user)->published()->createOne();
    $third = Link::factory()->for($user)->for($author)->published()->createOne();

    $links = app(GetUserLinks::class)->execute($user, new SearchLinkFormData(search: null, author_id: $author->id));

    expect($links->collect())
        ->toBeCollectionCanonicalizing([$first, $third]);
});
