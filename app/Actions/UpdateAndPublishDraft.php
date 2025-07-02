<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Requests\StoreLinkRequest;
use App\Models\Link;

class UpdateAndPublishDraft
{
    public function __construct(
        private readonly UpdateLink $updateLink,
    ) {}

    public function execute(Link $draft, StoreLinkRequest $data): void
    {
        $this->updateLink->execute($draft, $data);

        $draft->update(['published_at' => now()]);
    }
}
