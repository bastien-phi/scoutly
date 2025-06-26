<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;

it('returns trending tags tags', function (): void {
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
            ['id' => $php->id, 'label' => 'PHP', 'links_count' => 5],
            ['id' => $laravel->id, 'label' => 'Laravel', 'links_count' => 3],
            ['id' => $go->id, 'label' => 'Go', 'links_count' => 2],
        ]);
});
