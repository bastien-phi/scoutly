<?php

declare(strict_types=1);

use App\Actions\GetCommunityTags;
use App\Models\Link;
use App\Models\Tag;

it('gets community tags', function (): void {
    $first = Tag::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['label' => 'PHP']);
    $second = Tag::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['label' => 'Laravel']);

    $authors = app(GetCommunityTags::class)->execute(search: null);

    expect($authors)->toBeCollection([$second, $first]);
});

it('limits results to 30', function (): void {
    Tag::factory(31)
        ->has(Link::factory()->isPublic())
        ->create();

    $authors = app(GetCommunityTags::class)->execute(search: null);

    expect($authors)->toHaveCount(30);
});

it('returns distinct labels', function (): void {
    $php = Tag::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['label' => 'Php']);
    $PHP = Tag::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['label' => 'PHP']);

    $authors = app(GetCommunityTags::class)->execute(search: null);

    expect($authors)->toBeCollection([$php]);
});

it('filters tags', function (): void {
    $php = Tag::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['label' => 'PHP']);
    $php84 = Tag::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['label' => 'PHP 8.4']);
    $laravel = Tag::factory()
        ->has(Link::factory()->isPublic())
        ->createOne(['label' => 'Laravel']);

    $authors = app(GetCommunityTags::class)->execute(search: 'PHP');

    expect($authors)->toBeCollection([$php, $php84]);
});
