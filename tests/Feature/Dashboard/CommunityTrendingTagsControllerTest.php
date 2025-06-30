<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;

it('returns trending tags', function (): void {
    $go = Tag::factory()
        ->has(Link::factory(2)->published()->isPublic())
        ->has(Link::factory(3)->published()->isPrivate())
        ->create(['label' => 'Go']);

    $php = Tag::factory()
        ->has(Link::factory(2)->published()->isPublic())
        ->has(Link::factory(1)->draft()->isPublic())
        ->create(['label' => 'PHP']);

    $otherPHP = Tag::factory()
        ->has(Link::factory(3)->published()->isPublic())
        ->has(Link::factory(1)->draft()->isPublic())
        ->create(['label' => 'php']);

    $laravel = Tag::factory()
        ->has(Link::factory(3)->published()->isPublic())
        ->create(['label' => 'Laravel']);

    Tag::factory()
        ->has(Link::factory()->published()->isPublic())
        ->createOne();

    $this
        ->actingAs(User::factory()->createOne())
        ->getJson(route('api.dashboard.community-trending-tags'))
        ->assertOk()
        ->assertData([
            ['uuid' => $php->uuid, 'label' => 'PHP', 'links_count' => 5],
            ['uuid' => $laravel->uuid, 'label' => 'Laravel', 'links_count' => 3],
            ['uuid' => $go->uuid, 'label' => 'Go', 'links_count' => 2],
        ]);
});
