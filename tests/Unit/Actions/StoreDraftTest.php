<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\FindOrCreateTags;
use App\Actions\StoreDraft;
use App\Data\Requests\StoreDraftRequest;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

it('creates a draft link with the given data', function (): void {
    $user = User::factory()->createOne();
    $data = new StoreDraftRequest(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        is_public: false,
        author: 'John Doe',
        tags: ['PHP'],
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($user, 'John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $this->mockAction(FindOrCreateTags::class)
        ->with($user, ['PHP'])
        ->returns(fn () => Tag::factory(1)->create())
        ->in($tags);

    $link = app(StoreDraft::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $user->id,
        'url' => 'https://example.com',
        'title' => 'Example Title',
        'description' => 'Example Description',
        'author_id' => $author->id,
        'published_at' => null,
        'is_public' => false,
    ]);

    $this->assertDatabaseHas('link_tag', [
        'link_id' => $link->id,
        'tag_id' => $tags->first()->id,
    ]);
});

it('creates a draft link with minimal data', function (): void {
    $user = User::factory()->createOne();
    $data = new StoreDraftRequest(
        url: 'https://example.com',
        title: null,
        description: null,
        is_public: true,
        author: null,
        tags: null,
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($user, null)
        ->returns(fn (): null => null);

    $this->mockAction(FindOrCreateTags::class)
        ->with($user, null)
        ->returns(fn (): EloquentCollection => new EloquentCollection);

    $link = app(StoreDraft::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'user_id' => $user->id,
        'url' => 'https://example.com',
        'title' => null,
        'description' => null,
        'author_id' => null,
        'published_at' => null,
        'is_public' => true,
    ]);
});
