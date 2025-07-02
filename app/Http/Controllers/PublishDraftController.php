<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\UpdateAndPublishDraft;
use App\Data\Requests\StoreLinkRequest;
use App\Models\Link;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class PublishDraftController
{
    public function __invoke(
        StoreLinkRequest $data,
        Link $draft,
        UpdateAndPublishDraft $updateAndPublishDraft
    ): FoundationResponse {
        $updateAndPublishDraft->execute($draft, $data);

        return to_route('links.show', $draft);
    }
}
