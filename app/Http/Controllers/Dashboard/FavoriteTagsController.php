<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\Resources\JsonResource;
use App\Data\Resources\TagStatisticResource;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;

class FavoriteTagsController
{
    public function __invoke(#[CurrentUser] User $user): JsonResource
    {
        $tags = $user->tags()
            ->withCount([
                'links' => fn (Builder $query) => $query->wherePublished(),
            ])
            ->orderBy('links_count', 'desc')
            ->orderBy('id')
            ->limit(3)
            ->get();

        return JsonResource::collection($tags, TagStatisticResource::class);
    }
}
