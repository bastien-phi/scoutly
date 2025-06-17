<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\FindOrCreateTags;
use App\Actions\UpdateLink;
use App\Data\DraftFormData;
use App\Data\LinkFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Collection;
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
        tags: new Collection(['PHP'])
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($link->user, 'John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $this->mockAction(FindOrCreateTags::class)
        ->with($link->user, new Collection(['PHP']))
        ->returns(fn () => Tag::factory(1)->create())
        ->in($tags);

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

    $this->assertDatabaseHas('link_tag', [
        'link_id' => $link->id,
        'tag_id' => $tags->first()->id,
    ]);
});

it('updates a link with the draft data', function (): void {
    $link = Link::factory()
        ->published(Date::createFromFormat('!Y-m-d H:i:s', '2025-06-04 10:41:00'))
        ->createOne();
    $data = new DraftFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: null,
        author: 'John Doe',
        tags: new Collection(['PHP'])
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($link->user, 'John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $this->mockAction(FindOrCreateTags::class)
        ->with($link->user, new Collection(['PHP']))
        ->returns(fn () => Tag::factory(1)->create())
        ->in($tags);

    app(UpdateLink::class)->execute($link, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $link->user_id,
        'url' => 'https://example.com',
        'title' => 'Example Title',
        'description' => null,
        'author_id' => $author->id,
        'published_at' => '2025-06-04 10:41:00',
    ]);

    $this->assertDatabaseHas('link_tag', [
        'link_id' => $link->id,
        'tag_id' => $tags->first()->id,
    ]);
});
