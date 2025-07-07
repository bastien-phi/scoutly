<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;

it('returns a random community link', function (): void {
    $link = Link::factory()
        ->forAuthor()
        ->published()
        ->isPublic()
        ->createOne();

    Link::factory()
        ->published()
        ->isPrivate()
        ->createOne();

    Link::factory()
        ->draft()
        ->isPublic()
        ->createOne();

    $this
        ->actingAs(User::factory()->createOne())
        ->getJson(route('api.dashboard.random-community-link'))
        ->assertOk()
        ->assertData([
            'uuid' => $link->uuid,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'published_at' => $link->published_at?->toIso8601String(),
            'metadata' => null,
            'user' => [
                'uuid' => $link->user->uuid,
                'username' => $link->user->username,
            ],
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
        ->getJson(route('api.dashboard.random-community-link'))
        ->assertNotFound();
});
