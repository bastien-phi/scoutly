<?php

declare(strict_types=1);

use App\Actions\UpdateAndPublishDraft;
use App\Actions\UpdateLink;
use App\Data\LinkFormData;
use App\Models\Link;
use Illuminate\Support\Collection;

it('updates a draft link and publish it', function (): void {
    $link = Link::factory()
        ->draft()
        ->createOne();
    $data = new LinkFormData(
        url: 'https://example.com',
        title: 'Example Title',
        description: 'Example Description',
        is_public: false,
        author: 'John Doe',
        tags: new Collection(['PHP']),
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
