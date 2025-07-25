<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Requests\StoreDraftRequest;
use App\Events\LinkCreated;
use App\Models\Link;
use App\Models\User;

class StoreDraft
{
    public function __construct(
        private readonly FindOrCreateAuthor $findOrCreateAuthor,
        private readonly FindOrCreateTags $findOrCreateTags,
    ) {}

    public function execute(User $user, StoreDraftRequest $data): Link
    {
        $author = $this->findOrCreateAuthor->execute($user, $data->author);
        $tags = $this->findOrCreateTags->execute($user, $data->tags);

        $link = $user->links()->create([
            'url' => $data->url,
            'title' => $data->title,
            'description' => $data->description,
            'author_id' => $author?->id,
            'is_public' => $data->is_public,
        ]);

        $link->tags()->sync($tags);

        LinkCreated::dispatch($link);

        return $link;
    }
}
