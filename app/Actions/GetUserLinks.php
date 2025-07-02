<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Requests\GetUserLinksRequest;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserLinks
{
    /**
     * @return LengthAwarePaginator<int, \App\Models\Link>
     */
    public function execute(User $user, GetUserLinksRequest $data): LengthAwarePaginator
    {
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
     * @param  ?array<int, string>  $tagUuids
     * @return callable(Builder<Link>): void
     */
    private function filterTags(?array $tagUuids): callable
    {
        if ($tagUuids === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($tagUuids): void {
            $query->whereHas(
                'tags',
                fn (Builder $query) => $query->whereIn('uuid', $tagUuids),
                '=',
                count($tagUuids)
            );
        };
    }
}
