<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Requests\GetCommunityLinksRequest;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GetCommunityLinks
{
    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\Link>
     */
    public function execute(GetCommunityLinksRequest $data): LengthAwarePaginator
    {
        return Link::query()
            ->wherePublished()
            ->wherePublic()
            ->tap($this->search($data->search))
            ->tap($this->filterAuthor($data->author))
            ->tap($this->filterTags($data->tags))
            ->tap($this->filterUser($data->user))
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
     * @param  ?array<int, string>  $tags
     * @return callable(Builder<Link>): void
     */
    private function filterTags(?array $tags): callable
    {
        if ($tags === null || $tags === []) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($tags): void {
            $query->whereHas(
                'tags',
                fn (Builder $query) => $query->whereIn('label', $tags),
                '=',
                count($tags)
            );
        };
    }

    private function filterUser(?string $userUuid): callable
    {
        if ($userUuid === null) {
            return function (Builder $query): void {};
        }

        $user = User::query()->where('uuid', $userUuid)->first();
        if ($user === null) {
            return function (Builder $query): void {};
        }

        return function (Builder $query) use ($user): void {
            $query->where('user_id', $user->id);
        };
    }
}
