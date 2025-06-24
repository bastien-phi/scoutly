<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\SearchLinkFormData;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GetUserLinks
{
    /**
     * @return LengthAwarePaginator<int, \App\Models\Link>
     */
    public function execute(
        User $user,
        SearchLinkFormData $data,
    ): LengthAwarePaginator {
        return $user->links()
            ->wherePublished()
            ->tap($this->search($data->search))
            ->tap($this->filterAuthor($data->author_id))
            ->tap($this->filterTags($data->tags))
            ->latest('published_at')
            ->latest('id')
            ->with(['author', 'tags'])
            ->paginate();
    }

    /**
     * @return callable(Builder<Link>): void
     */
    private function search(?string $search): callable
    {
        if ($search === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($search): void {
            $query->whereIn(
                'id',
                Link::search($search)->keys()
            );
        };
    }

    /**
     * @return callable(Builder<Link>): void
     */
    private function filterAuthor(?int $authorId): callable
    {
        if ($authorId === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($authorId): void {
            $query->where('author_id', $authorId);
        };
    }

    /**
     * @param  \Illuminate\Support\Collection<int, int>  $tagIds
     * @return callable(Builder<Link>): void
     */
    private function filterTags(Collection $tagIds): callable
    {
        if ($tagIds->isEmpty()) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($tagIds): void {
            $query->whereHas(
                'tags',
                fn (Builder $query) => $query->whereIn('id', $tagIds),
                '=',
                $tagIds->count()
            );
        };
    }
}
