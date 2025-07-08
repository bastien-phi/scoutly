<?php

declare(strict_types=1);

use App\Actions\UpdateAuthor;
use App\Data\Requests\UpdateAuthorRequest;
use App\Models\Author;

it('updates an author with the given data', function (): void {
    $author = Author::factory()->createOne();
    $data = new UpdateAuthorRequest(
        name: 'John Doe',
    );

    app(UpdateAuthor::class)->execute($author, $data);

    $this->assertDatabaseHas(Author::class, [
        'id' => $author->id,
        'name' => 'John Doe',
    ]);
});
