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
            ->tap($this->filterAuthor($data->author_uuid))
            ->tap($this->filterTags($data->tag_uuids))
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
    private function filterAuthor(?string $authorUuid): callable
    {
        if ($authorUuid === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($authorUuid): void {
            $query->whereRelation('author', 'uuid', $authorUuid);
        };
    }

    /**
     * @param  \Illuminate\Support\Collection<int, string>  $tagUuids
     * @return callable(Builder<Link>): void
     */
    private function filterTags(Collection $tagUuids): callable
    {
        if ($tagUuids->isEmpty()) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($tagUuids): void {
            $query->whereHas(
                'tags',
                fn (Builder $query) => $query->whereIn('uuid', $tagUuids),
                '=',
                $tagUuids->count()
            );
        };
    }
}
