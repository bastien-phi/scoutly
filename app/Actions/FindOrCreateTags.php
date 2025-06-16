<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FindOrCreateTags
{
    /**
     * @param  Collection<int, string>  $tags
     * @return EloquentCollection<int, Tag>
     */
    public function execute(Collection $tags): EloquentCollection
    {
        return $tags->map($this->findOrCreateTag(...))
            ->pipeInto(EloquentCollection::class)
            ->unique();
    }

    private function findOrCreateTag(string $label): Tag
    {
        return Tag::query()
            ->whereRaw('LOWER(label) = ?', Str::lower($label))
            ->firstOr(
                fn () => Tag::query()->create(['label' => $label])
            );
    }
}
