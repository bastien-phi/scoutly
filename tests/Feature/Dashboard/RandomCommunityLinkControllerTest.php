<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;

it('returns a random community links', function (): void {
    $user = User::factory()->createOne();

    $link = Link::factory()
        ->recycle($user)
        ->forAuthor()
        ->published()
        ->createOne();

    Link::factory()
        ->recycle($user)
        ->draft()
        ->createOne();

    $this
        ->actingAs($user)
        ->getJson(route('api.dashboard.random-link'))
        ->assertOk()
        ->assertData([
            'id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'published_at' => $link->published_at?->toIso8601String(),
            'is_public' => $link->is_public,
            'created_at' => $link->created_at->toIso8601String(),
            'updated_at' => $link->updated_at->toIso8601String(),
            'author' => [
                'id' => $link->author->id,
                'name' => $link->author->name,
            ],
            'tags' => [],
        ]);
});
