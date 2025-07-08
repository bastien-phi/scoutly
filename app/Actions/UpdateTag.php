<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Requests\UpdateTagRequest;
use App\Models\Tag;

class UpdateTag
{
    public function execute(Tag $tag, UpdateTagRequest $data): void
    {
        $tag->update([
            'label' => $data->label,
        ]);
    }
}
