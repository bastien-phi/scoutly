<?php

declare(strict_types=1);

use App\Actions\FindOrCreateAuthor;
use App\Actions\FindOrCreateTags;
use App\Actions\StoreLink;
use App\Data\LinkFormData;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

it('creates a link with the given data', function (): void {
    $user = User::factory()->createOne();
    $data = new LinkFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        is_public: false,
        author: 'John Doe',
        tags: new Collection(['PHP']),
    );

    $this->freezeSecond();

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($user, 'John Doe')
        ->returns(fn () => Author::factory()->createOne())
        ->in($author);

    $this->mockAction(FindOrCreateTags::class)
        ->with($user, new Collection(['PHP']))
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
    $data = new LinkFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        is_public: true,
        author: null,
        tags: new Collection,
    );

    $this->freezeSecond();

    $this->mockAction(FindOrCreateAuthor::class)
        ->with($user, null)
        ->returns(fn (): null => null);

    $this->mockAction(FindOrCreateTags::class)
        ->with($user, new Collection)
        ->returns(fn (): \Illuminate\Database\Eloquent\Collection => new EloquentCollection);

    $link = app(StoreLink::class)->execute($user, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'author_id' => null,
    ]);
});
