<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;

it('returns favorite tags', function (): void {
    $user = User::factory()->createOne();

    $go = Tag::factory()
        ->recycle($user)
        ->has(Link::factory(2)->published())
        ->create(['label' => 'Go']);

    $php = Tag::factory()
        ->recycle($user)
        ->has(Link::factory(5)->published())
        ->create(['label' => 'PHP']);

    $laravel = Tag::factory()
        ->recycle($user)
        ->has(Link::factory(3)->published())
        ->create(['label' => 'Laravel']);

    Tag::factory()->recycle($user)->hasLinks(1)->createOne();

    $this
        ->actingAs($user)
        ->getJson(route('api.dashboard.favorite-tags'))
        ->assertOk()
        ->assertData([
            ['id' => $php->id, 'label' => 'PHP', 'links_count' => 5],
            ['id' => $laravel->id, 'label' => 'Laravel', 'links_count' => 3],
            ['id' => $go->id, 'label' => 'Go', 'links_count' => 2],
        ]);
});
