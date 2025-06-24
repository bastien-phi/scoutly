<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class FindOrCreateTags
{
    /**
     * @param  Collection<int, string>  $tags
     * @return EloquentCollection<int, Tag>
     */
    public function execute(User $user, Collection $tags): EloquentCollection
    {
        return $tags->map(fn (string $tag) => $user->tags()->firstOrCreate(['label' => $tag]))
            ->pipeInto(EloquentCollection::class)
            ->unique();
    }
}
