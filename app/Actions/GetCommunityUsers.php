<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetCommunityUsers
{
    /**
     * @return Collection<int, User>
     */
    public function execute(?string $search): Collection
    {
        return User::query()
            ->whereExists(
                Link::query()
                    ->wherePublic()
                    ->wherePublished()
                    ->whereColumn('users.id', 'links.user_id')
            )
            ->tap($this->search($search))
            ->orderBy('username')
            ->limit(30)
            ->get();
    }

    /**
     * @return callable(Builder<User>): void
     */
    private function search(?string $search): callable
    {
        if ($search === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($search): void {
            $query->whereLike('username', "%{$search}%");
        };
    }
}
