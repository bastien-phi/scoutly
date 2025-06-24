<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\SearchCommunityLinkFormData;
use App\Models\Link;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

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
}
