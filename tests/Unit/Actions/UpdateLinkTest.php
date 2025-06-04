<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\UpdateLink;
use App\Data\LinkFormData;
use App\Models\Author;
use App\Models\Link;
use Illuminate\Support\Facades\Date;

it('updates a link with the given data', function (): void {
    $link = Link::factory()
        ->published(Date::createFromFormat('!Y-m-d H:i:s', '2025-06-04 10:41:00'))
        ->createOne();
    $data = new LinkFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        author: 'John Doe',
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with('John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    app(UpdateLink::class)->execute($link, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $link->user_id,
        'url' => 'https://example.com',
        'title' => 'Example Title',
        'description' => 'Example Description',
        'author_id' => $author->id,
        'published_at' => '2025-06-04 10:41:00',
    ]);
});
