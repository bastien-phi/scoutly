<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Requests\UpdateAuthorRequest;
use App\Models\Author;

class UpdateAuthor
{
    public function execute(Author $author, UpdateAuthorRequest $data): void
    {
        $author->update([
            'name' => $data->name,
        ]);
    }
}
