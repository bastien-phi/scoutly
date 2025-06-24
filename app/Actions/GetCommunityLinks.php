<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Link;
use Illuminate\Pagination\LengthAwarePaginator;

class GetCommunityLinks
{
    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\Link>
     */
    public function execute(): LengthAwarePaginator
    {
        return Link::query()
            ->wherePublished()
            ->wherePublic()
            ->latest('published_at')
            ->latest('id')
            ->with(['author', 'user', 'tags'])
            ->paginate();
    }
}
