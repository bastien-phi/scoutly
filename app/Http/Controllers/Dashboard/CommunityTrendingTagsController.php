<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\TagStatisticData;
use App\Http\Resources\DataCollectionResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class CommunityTrendingTagsController
{
    public function __invoke(): DataCollectionResource
    {
        $tags = Tag::query()
            ->withCount([
                'links' => fn (Builder $query) => $query->wherePublished()->wherePublic(),
            ])
            ->orderBy('links_count', 'desc')
            ->orderBy('id')
            ->limit(3)
            ->get();

        return DataCollectionResource::make($tags, TagStatisticData::class);
    }
}
