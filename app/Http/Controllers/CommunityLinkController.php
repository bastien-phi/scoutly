<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityLinks;
use App\Data\CommunityLinkData;
use Inertia\Inertia;
use Inertia\Response;

class CommunityLinkController
{
    public function index(GetCommunityLinks $getCommunityLinks): Response
    {
        $links = $getCommunityLinks->execute();

        return Inertia::render('community-links/index', [
            'links' => $links->currentPage() === 1 ? CommunityLinkData::collect($links) : Inertia::deepMerge(CommunityLinkData::collect($links)),
        ]);
    }
}
