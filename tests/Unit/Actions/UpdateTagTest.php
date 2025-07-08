<?php

declare(strict_types=1);

use App\Actions\UpdateTag;
use App\Data\Requests\UpdateTagRequest;
use App\Models\Tag;

it('updates a tag with the given data', function (): void {
    $tag = Tag::factory()->createOne();
    $data = new UpdateTagRequest(
        label: 'Laravel',
    );

    app(UpdateTag::class)->execute($tag, $data);

    $this->assertDatabaseHas(Tag::class, [
        'id' => $tag->id,
        'label' => 'Laravel',
    ]);
});
