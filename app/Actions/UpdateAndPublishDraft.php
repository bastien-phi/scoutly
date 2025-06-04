<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\LinkFormData;
use App\Models\Link;

class UpdateAndPublishDraft
{
    public function __construct(
        private UpdateLink $updateLink,
    ) {}

    public function execute(Link $draft, LinkFormData $data): void
    {
        $this->updateLink->execute($draft, $data);

        $draft->update(['published_at' => now()]);
    }
}
