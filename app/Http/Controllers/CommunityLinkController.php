<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityLinks;
use App\Data\CommunityLinkData;
use App\Data\SearchCommunityLinkFormData;
use App\Http\Requests\GetCommunityLinksRequest;
use Inertia\Inertia;
use Inertia\Response;

class CommunityLinkController
{
    public function index(
        GetCommunityLinksRequest $request,
        GetCommunityLinks $getCommunityLinks
    ): Response {
        $data = SearchCommunityLinkFormData::from($request);
        $links = $getCommunityLinks->execute($data);

        return Inertia::render('community-links/index', [
            'request' => $data->onlyNotNull(),
            'links' => $links->currentPage() === 1 ? CommunityLinkData::collect($links) : Inertia::deepMerge(CommunityLinkData::collect($links)),
        ]);
    }
}
