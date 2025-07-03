<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\Resources\CommunityLinkResource;
use App\Data\Resources\JsonResource;
use App\Models\Link;

class RandomCommunityLinkController
{
    public function __invoke(): JsonResource
    {
        $link = Link::query()
            ->wherePublished()
            ->wherePublic()
            ->with(['author', 'user', 'tags'])
            ->randomOrFail();

        return JsonResource::make(CommunityLinkResource::from($link));
    }
}
