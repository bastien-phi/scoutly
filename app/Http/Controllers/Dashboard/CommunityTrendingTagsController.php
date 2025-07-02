<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\Resources\JsonResource;
use App\Data\Resources\TagStatisticResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class CommunityTrendingTagsController
{
    public function __invoke(): JsonResource
    {
        $tagStatQuery = Tag::query()->withCount([
            'links' => fn (Builder $query) => $query->wherePublished()->wherePublic(),
        ]);

        $tags = Tag::query()
            ->from($tagStatQuery, 'tag_stats')
            ->selectRaw('min(uuid) as uuid, label, sum(links_count) as links_count')
            ->groupBy('label')
            ->orderBy('links_count', 'desc')
            ->orderBy('uuid')
            ->limit(3)
            ->get();

        return JsonResource::collection($tags, TagStatisticResource::class);
    }
}
