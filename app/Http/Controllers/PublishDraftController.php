<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\UpdateAndPublishDraft;
use App\Data\LinkFormData;
use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class PublishDraftController
{
    public function __invoke(
        StoreLinkRequest $request,
        Link $draft,
        UpdateAndPublishDraft $updateAndPublishDraft
    ): FoundationResponse {
        $updateAndPublishDraft->execute($draft, LinkFormData::from($request));

        return Inertia::location(route('links.show', $draft));
    }
}
