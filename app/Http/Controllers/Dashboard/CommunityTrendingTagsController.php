<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\TagStatisticData;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\DataCollection;

class CommunityTrendingTagsController
{
    /**
     * @return DataCollection<int, TagStatisticData>
     */
    public function __invoke(): DataCollection
    {
        $tags = Tag::query()
            ->withCount([
                'links' => fn (Builder $query) => $query->wherePublished()->wherePublic(),
            ])
            ->orderBy('links_count', 'desc')
            ->orderBy('id')
            ->limit(3)
            ->get();

        return TagStatisticData::collect($tags, DataCollection::class);
    }
}
