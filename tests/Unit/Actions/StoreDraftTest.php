<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\FindOrCreateTags;
use App\Actions\StoreDraft;
use App\Data\DraftFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

it('creates a draft link with the given data', function (): void {
    $user = User::factory()->createOne();
    $data = new DraftFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        author: 'John Doe',
        tags: new Collection(['PHP']),
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with('John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $this->mockAction(FindOrCreateTags::class)
        ->with(new Collection(['PHP']))
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
    ]);

    $this->assertDatabaseHas('link_tag', [
        'link_id' => $link->id,
        'tag_id' => $tags->first()->id,
    ]);
});

it('creates a draft link with minimal data', function (): void {
    $user = User::factory()->createOne();
    $data = new DraftFormData(
        url: 'https://example.com',
        title: null,
        description: null,
        author: null,
        tags: new Collection,
    );

    $this->mockAction(FindOrCreateAuthor::class)
        ->with(null)
        ->returns(fn () => null);

    $this->mockAction(FindOrCreateTags::class)
        ->with(new Collection)
        ->returns(fn () => new EloquentCollection);

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
