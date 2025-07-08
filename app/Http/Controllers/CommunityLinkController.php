<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityLinks;
use App\Data\Requests\GetCommunityLinksRequest;
use App\Data\Resources\CommunityLinkResource;
use App\Data\Resources\UserResource;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class CommunityLinkController
{
    public function index(
        GetCommunityLinksRequest $data,
        GetCommunityLinks $getCommunityLinks
    ): Response {
        $links = $getCommunityLinks->execute($data);
        $user = $data->user !== null ? User::query()->where('uuid', $data->user)->first() : null;

        return Inertia::render('community-links/index', [
            'request' => $data->onlyNotNull(),
            'links' => $links->currentPage() === 1 ? CommunityLinkResource::collect($links) : Inertia::deepMerge(CommunityLinkResource::collect($links)),
            'user' => $user !== null ? UserResource::from($user) : null,
        ]);
    }
}
