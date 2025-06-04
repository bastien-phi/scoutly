<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\UpdateDraft;
use App\Data\DraftFormData;
use App\Models\Author;
use App\Models\Link;

it('updates a draft link with the given data', function (): void {
    $link = Link::factory()
        ->draft()
        ->createOne();
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

    app(UpdateDraft::class)->execute($link, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $link->user_id,
        'url' => 'https://example.com',
        'title' => 'Example Title',
        'description' => 'Example Description',
        'author_id' => $author->id,
        'published_at' => null,
    ]);
});
