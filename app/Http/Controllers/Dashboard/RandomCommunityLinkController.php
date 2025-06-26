<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\CommunityLinkData;
use App\Http\Resources\DataResource;
use App\Models\Link;

class RandomCommunityLinkController
{
    public function __invoke(): DataResource
    {
        $link = Link::query()
            ->wherePublished()
            ->wherePublic()
            ->with(['author', 'user', 'tags'])
            ->random();

        return DataResource::make($link, CommunityLinkData::class);
    }
}
