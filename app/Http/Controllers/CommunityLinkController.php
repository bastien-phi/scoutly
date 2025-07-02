<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityLinks;
use App\Data\Requests\GetCommunityLinksRequest;
use App\Data\Resources\CommunityLinkResource;
use Inertia\Inertia;
use Inertia\Response;

class CommunityLinkController
{
    public function index(
        GetCommunityLinksRequest $data,
        GetCommunityLinks $getCommunityLinks
    ): Response {
        $links = $getCommunityLinks->execute($data);

        return Inertia::render('community-links/index', [
            'request' => $data->onlyNotNull(),
            'links' => $links->currentPage() === 1 ? CommunityLinkResource::collect($links) : Inertia::deepMerge(CommunityLinkResource::collect($links)),
        ]);
    }
}
