<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;

it('returns a random link', function (): void {
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
            'uuid' => $link->uuid,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'published_at' => $link->published_at?->toIso8601String(),
            'is_public' => $link->is_public,
            'metadata' => null,
            'created_at' => $link->created_at->toIso8601String(),
            'updated_at' => $link->updated_at->toIso8601String(),
            'author' => [
                'uuid' => $link->author->uuid,
                'name' => $link->author->name,
            ],
            'tags' => [],
        ]);
});

it('returns not found if no link was found', function (): void {
    $this
        ->actingAs(User::factory()->createOne())
        ->getJson(route('api.dashboard.random-link'))
        ->assertNotFound();
});
