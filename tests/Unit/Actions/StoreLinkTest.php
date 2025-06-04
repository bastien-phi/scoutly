<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\StoreLink;
use App\Data\LinkFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\User;

it('creates a link with the given data', function (): void {
    $user = User::factory()->createOne();
    $data = new LinkFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        author: 'John Doe',
    );

    $this->freezeSecond();

    $this->mockAction(FindOrCreateAuthor::class)
        ->with('John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $link = app(StoreLink::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $user->id,
        'url' => 'https://example.com',
        'title' => 'Example Title',
        'description' => 'Example Description',
        'author_id' => $author->id,
        'published_at' => now(),
    ]);
});

it('creates a link without author', function (): void {
    $user = User::factory()->createOne();
    $data = new LinkFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        author: null,
    );

    $this->freezeSecond();

    $this->mockAction(FindOrCreateAuthor::class)
        ->with(null)
        ->returns(fn () => null);

    $link = app(StoreLink::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'author_id' => null,
    ]);
});
