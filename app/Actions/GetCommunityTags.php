<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetCommunityTags
{
    /**
     * @return Collection<int, Tag>
     */
    public function execute(?string $search = null): Collection
    {
        return Tag::query()
            ->distinct('label')
            ->wherePublic()
            ->tap($this->search($search))
            ->orderBy('label')
            ->orderBy('id')
            ->limit(30)
            ->get();
    }

    /**
     * @return callable(Builder<Tag>): void
     */
    private function search(?string $search): callable
    {
        if ($search === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($search): void {
            $query->whereLike('label', "%{$search}%");
        };
    }
}
