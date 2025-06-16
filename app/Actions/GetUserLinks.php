<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\SearchLinkFormData;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

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
            ->when(
                $data->search !== null,
                fn (Builder $query) => $query->whereIn(
                    'id',
                    Link::search($data->search)->keys()
                ),
            )
            ->when(
                $data->author_id !== null,
                fn (Builder $query) => $query->where('author_id', $data->author_id),
            )
            ->latest('published_at')
            ->latest('created_at')
            ->with(['author', 'tags'])
            ->paginate();
    }
}
