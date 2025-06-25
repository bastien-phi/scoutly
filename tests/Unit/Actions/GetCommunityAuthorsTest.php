<?php

declare(strict_types=1);

use App\Actions\GetCommunityAuthors;
use App\Models\Author;
use App\Models\Link;

it('gets community authors', function (): void {
    $john = Author::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['name' => 'John Doe']);
    $jane = Author::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['name' => 'Jane Smith']);

    $authors = app(GetCommunityAuthors::class)->execute(search: null);

    expect($authors)->toBeCollection([$jane, $john]);
});

it('limits results to 30', function (): void {
    Author::factory(31)
        ->has(Link::factory()->isPublic())
        ->create();

    $authors = app(GetCommunityAuthors::class)->execute(search: null);

    expect($authors)->toHaveCount(30);
});

it('returns distinct names', function (): void {
    $john = Author::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['name' => 'John Doe']);
    $JOHN = Author::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['name' => 'JOHN DOE']);

    $authors = app(GetCommunityAuthors::class)->execute(search: null);

    expect($authors)->toBeCollection([$john]);
});

it('filters authors', function (): void {
    $john = Author::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['name' => 'John Doe']);
    $jane = Author::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['name' => 'Jane Doe']);
    $patrick = Author::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['name' => 'Patrick Smith']);

    $authors = app(GetCommunityAuthors::class)->execute(search: 'do');

    expect($authors)->toBeCollection([$jane, $john]);
});
