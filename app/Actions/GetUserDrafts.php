<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserDrafts
{
    /**
     * @return LengthAwarePaginator<int, \App\Models\Link>
     */
    public function execute(User $user): LengthAwarePaginator
    {
        return $user->links()
            ->whereDraft()
            ->latest('created_at')
            ->with('author')
            ->paginate();
    }
}
