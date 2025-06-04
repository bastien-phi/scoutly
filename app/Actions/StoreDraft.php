<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\DraftFormData;
use App\Models\Link;
use App\Models\User;

class StoreDraft
{
    public function __construct(
        private FindOrCreateAuthor $findOrCreateAuthor
    ) {}

    public function execute(User $user, DraftFormData $data): Link
    {
        $author = $this->findOrCreateAuthor->execute($data->author);

        return $user->links()->create([
            'url' => $data->url,
            'title' => $data->title,
            'description' => $data->description,
            'author_id' => $author?->id,
        ]);
    }
}
