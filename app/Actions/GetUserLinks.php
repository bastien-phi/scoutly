<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserLinks
{
    /**
     * @return LengthAwarePaginator<int, \App\Models\Link>
     */
    public function execute(User $user): LengthAwarePaginator
    {
        return $user->links()
            ->wherePublished()
            ->latest('published_at')
            ->latest('created_at')
            ->with('author')
            ->paginate();
    }
}
