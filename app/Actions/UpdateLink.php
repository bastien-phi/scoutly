<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\DraftFormData;
use App\Data\LinkFormData;
use App\Models\Link;

class UpdateLink
{
    public function __construct(
        private FindOrCreateAuthor $findOrCreateAuthor,
        private FindOrCreateTags $findOrCreateTags,
    ) {}

    public function execute(Link $link, LinkFormData|DraftFormData $data): void
    {
        $author = $this->findOrCreateAuthor->execute($link->user, $data->author);
        $tags = $this->findOrCreateTags->execute($link->user, $data->tags);

        $link->update([
            'url' => $data->url,
            'title' => $data->title,
            'description' => $data->description,
            'author_id' => $author?->id,
        ]);

        $link->tags()->sync($tags);
    }
}
