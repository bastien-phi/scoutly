<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Requests\StoreLinkRequest;
use App\Events\LinkCreated;
use App\Models\Link;
use App\Models\User;

class StoreLink
{
    public function __construct(
        private readonly FindOrCreateAuthor $findOrCreateAuthor,
        private readonly FindOrCreateTags $findOrCreateTags,
    ) {}

    public function execute(User $user, StoreLinkRequest $data): Link
    {
        $author = $this->findOrCreateAuthor->execute($user, $data->author);
        $tags = $this->findOrCreateTags->execute($user, $data->tags);

        $link = $user->links()->create([
            'url' => $data->url,
            'title' => $data->title,
            'description' => $data->description,
            'author_id' => $author?->id,
            'published_at' => now(),
            'is_public' => $data->is_public,
        ]);

        $link->tags()->sync($tags);

        LinkCreated::dispatch($link);

        return $link;
    }
}
