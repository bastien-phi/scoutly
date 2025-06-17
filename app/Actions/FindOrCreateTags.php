<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FindOrCreateTags
{
    /**
     * @param  Collection<int, string>  $tags
     * @return EloquentCollection<int, Tag>
     */
    public function execute(User $user, Collection $tags): EloquentCollection
    {
        return $tags->map(fn (string $tag) => $this->findOrCreateTag($user, $tag))
            ->pipeInto(EloquentCollection::class)
            ->unique();
    }

    private function findOrCreateTag(User $user, string $label): Tag
    {
        return $user->tags()
            ->whereRaw('LOWER(label) = ?', Str::lower($label))
            ->firstOr(
                fn () => $user->tags()->create(['label' => $label])
            );
    }
}
