<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Models\Author;

it('create new author if not exists', function (): void {
    $authorName = 'John Doe';

    $author = app(FindOrCreateAuthor::class)->execute($authorName);

    $this->assertDatabaseHas(Author::class, [
        'id' => $author->id,
        'name' => $authorName,
    ]);
});

it('find existing author', function (): void {
    $author = Author::factory()->createOne(['name' => 'Jane Doe']);
    $authorName = 'jane doe';

    $found = app(FindOrCreateAuthor::class)->execute($authorName);

    expect($found->is($author))->toBeTrue();
});
