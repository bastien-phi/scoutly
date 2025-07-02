<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\FindOrCreateTags;
use App\Actions\StoreLink;
use App\Data\Requests\StoreLinkRequest;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

it('creates a link with the given data', function (): void {
    $user = User::factory()->createOne();
    $data = new StoreLinkRequest(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        is_public: false,
        author: 'John Doe',
        tags: ['PHP'],
    );

    $this->freezeSecond();

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($user, 'John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $this->mockAction(FindOrCreateTags::class)
        ->with($user, ['PHP'])
        ->returns(fn () => Tag::factory(1)->create(['label' => 'PHP']))
        ->in($tags);

    $link = app(StoreLink::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $user->id,
        'url' => 'https://example.com',
        'title' => 'Example Title',
        'description' => 'Example Description',
        'author_id' => $author->id,
        'published_at' => now(),
        'is_public' => false,
    ]);

    $this->assertDatabaseHas('link_tag', [
        'link_id' => $link->id,
        'tag_id' => $tags->first()->id,
    ]);
});

it('creates a link without author nor tag', function (): void {
    $user = User::factory()->createOne();
    $data = new StoreLinkRequest(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        is_public: true,
        author: null,
        tags: null,
    );

    $this->freezeSecond();

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($user, null)
        ->returns(fn (): null => null);

    $this->mockAction(FindOrCreateTags::class)
        ->with($user, null)
        ->returns(fn (): Collection => new Collection);

    $link = app(StoreLink::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'author_id' => null,
    ]);
});
