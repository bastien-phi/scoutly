<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\SearchCommunityLinkFormData;
use App\Models\Link;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GetCommunityLinks
{
    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\Link>
     */
    public function execute(SearchCommunityLinkFormData $data): LengthAwarePaginator
    {
        return Link::query()
            ->wherePublished()
            ->wherePublic()
            ->tap($this->search($data->search))
            ->tap($this->filterAuthor($data->author))
            ->tap($this->filterTags($data->tags))
            ->latest('published_at')
            ->latest('id')
            ->with(['author', 'user', 'tags'])
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
    private function filterAuthor(?string $author): callable
    {
        if ($author === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($author): void {
            $query->whereRelation('author', 'name', $author);
        };
    }

    /**
     * @param  Collection<int, string>  $tags
     * @return callable(Builder<Link>): void
     */
    private function filterTags(Collection $tags): callable
    {
        if ($tags->isEmpty()) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($tags): void {
            $query->whereHas(
                'tags',
                fn (Builder $query) => $query->whereIn('label', $tags),
                '=',
                $tags->count()
            );
        };
    }
}
