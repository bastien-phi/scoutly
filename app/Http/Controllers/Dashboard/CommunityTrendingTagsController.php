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
        $tagStatQuery = Tag::query()->withCount([
            'links' => fn (Builder $query) => $query->wherePublished()->wherePublic(),
        ]);

        $tags = Tag::query()
            ->from($tagStatQuery, 'tag_stats')
            ->selectRaw('min(id) as id, label, sum(links_count) as links_count')
            ->groupBy('label')
            ->orderBy('links_count', 'desc')
            ->orderBy('id')
            ->limit(3)
            ->get();

        return DataCollectionResource::make($tags, TagStatisticData::class);
    }
}
