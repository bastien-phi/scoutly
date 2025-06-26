<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;

it('returns a random link', function (): void {
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
            'id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'published_at' => $link->published_at?->toIso8601String(),
            'user' => [
                'id' => $link->user->id,
                'username' => $link->user->username,
            ],
            'author' => [
                'id' => $link->author->id,
                'name' => $link->author->name,
            ],
            'tags' => [],
        ]);
});
