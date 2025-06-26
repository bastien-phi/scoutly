<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\TagStatisticData;
use App\Http\Resources\DataCollectionResource;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;

class FavoriteTagsController
{
    public function __invoke(#[CurrentUser] User $user): DataCollectionResource
    {
        $tags = $user->tags()
            ->withCount([
                'links' => fn (Builder $query) => $query->wherePublished(),
            ])
            ->orderBy('links_count', 'desc')
            ->orderBy('id')
            ->limit(3)
            ->get();

        return DataCollectionResource::make($tags, TagStatisticData::class);
    }
}
