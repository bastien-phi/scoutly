<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetCommunityAuthors
{
    /**
     * @return Collection<int, Author>
     */
    public function execute(?string $search): Collection
    {
        return Author::query()
            ->distinct('name')
            ->wherePublic()
            ->tap($this->search($search))
            ->orderBy('name')
            ->orderBy('id')
            ->limit(30)
            ->get();
    }

    /**
     * @return callable(Builder<Author>): void
     */
    private function search(?string $search): callable
    {
        if ($search === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($search): void {
            $query->whereLike('name', "%{$search}%");
        };
    }
}
