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
     * @param  ?array<int, string>  $tags
     * @return EloquentCollection<int, Tag>
     */
    public function execute(User $user, ?array $tags): EloquentCollection
    {
        if ($tags === null) {
            return new EloquentCollection;
        }

        return new Collection($tags)
            ->map(fn (string $tag) => $user->tags()->firstOrCreate(['label' => $tag]))
            ->pipeInto(EloquentCollection::class)
            ->unique();
    }
}
