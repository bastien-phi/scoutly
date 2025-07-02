<?php

declare(strict_types=1);

use App\Actions\UpdateAndPublishDraft;
use App\Actions\UpdateLink;
use App\Data\Requests\StoreLinkRequest;
use App\Models\Link;

it('updates a draft link and publish it', function (): void {
    $link = Link::factory()
        ->draft()
        ->createOne();
    $data = new StoreLinkRequest(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        is_public: false,
        author: 'John Doe',
        tags: ['PHP'],
    );

    $this->freezeSecond();

    $this->mockAction(UpdateLink::class)
        ->with($link, $data);

    app(UpdateAndPublishDraft::class)->execute($link, $data);

    $this->assertDatabaseHas(Link::class, [
        'id' => $link->id,
        'published_at' => now(),
    ]);
});
