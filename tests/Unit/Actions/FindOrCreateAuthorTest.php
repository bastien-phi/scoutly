<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Models\Author;
use App\Models\User;

it('create new author if not exists', function (): void {
    $user = User::factory()->createOne();

    $authorName = 'John Doe';

    $author = app(FindOrCreateAuthor::class)->execute($user, $authorName);

    $this->assertDatabaseHas(Author::class, [
        'id' => $author->id,
        'user_id' => $user->id,
        'name' => $authorName,
    ]);
});

it('find existing author', function (): void {
    $user = User::factory()->createOne();
    $author = Author::factory()->for($user)->createOne(['name' => 'Jane Doe']);

    $authorName = 'jane doe';

    $found = app(FindOrCreateAuthor::class)->execute($user, $authorName);

    expect($found->is($author))->toBeTrue();
});
