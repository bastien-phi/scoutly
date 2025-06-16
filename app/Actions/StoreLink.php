<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\LinkFormData;
use App\Models\Link;
use App\Models\User;

class StoreLink
{
    public function __construct(
        private FindOrCreateAuthor $findOrCreateAuthor,
        private FindOrCreateTags $findOrCreateTags,
    ) {}

    public function execute(User $user, LinkFormData $data): Link
    {
        $author = $this->findOrCreateAuthor->execute($data->author);
        $tags = $this->findOrCreateTags->execute($data->tags);

        $link = $user->links()->create([
            'url' => $data->url,
            'title' => $data->title,
            'description' => $data->description,
            'author_id' => $author?->id,
            'published_at' => now(),
        ]);

        $link->tags()->sync($tags);

        return $link;
    }
}
