<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\StoreDraft;
use App\Data\DraftFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\User;

it('creates a draft link with the given data', function (): void {
    $user = User::factory()->createOne();
    $data = new DraftFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        author: 'John Doe',
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with('John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $link = app(StoreDraft::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $user->id,
        'url' => 'https://example.com',
        'title' => 'Example Title',
        'description' => 'Example Description',
        'author_id' => $author->id,
        'published_at' => null,
    ]);
});

it('creates a draft link with minimal data', function (): void {
    $user = User::factory()->createOne();
    $data = new DraftFormData(
        url: 'https://example.com',
        title: null,
        description: null,
        author: null,
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with(null)
        ->returns(fn () => null);

    $link = app(StoreDraft::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $user->id,
        'url' => 'https://example.com',
        'title' => null,
        'description' => null,
        'author_id' => null,
        'published_at' => null,
    ]);
});
