<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\LinkFormData;
use App\Models\Link;

class UpdateLink
{
    public function __construct(
        private FindOrCreateAuthor $findOrCreateAuthor
    ) {}

    public function execute(Link $link, LinkFormData $data): void
    {
        $author = $this->findOrCreateAuthor->execute($data->author);

        $link->update([
            'url' => $data->url,
            'title' => $data->title,
            'description' => $data->description,
            'author_id' => $author?->id,
        ]);
    }
}
