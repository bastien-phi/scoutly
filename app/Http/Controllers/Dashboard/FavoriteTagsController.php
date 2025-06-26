<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\TagStatisticData;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\DataCollection;

class FavoriteTagsController
{
    /**
     * @return DataCollection<int, TagStatisticData>
     */
    public function __invoke(#[CurrentUser] User $user): DataCollection
    {
        $tags = $user->tags()
            ->withCount([
                'links' => fn (Builder $query) => $query->wherePublished(),
            ])
            ->orderBy('links_count', 'desc')
            ->orderBy('id')
            ->limit(3)
            ->get();

        return TagStatisticData::collect($tags, DataCollection::class);
    }
}
