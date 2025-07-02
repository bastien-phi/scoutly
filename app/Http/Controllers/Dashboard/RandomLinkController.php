<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\Resources\JsonResource;
use App\Data\Resources\LinkResource;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

class RandomLinkController
{
    public function __invoke(#[CurrentUser] User $user): JsonResource
    {
        $link = $user->links()
            ->wherePublished()
            ->with(['author', 'tags'])
            ->random();

        return JsonResource::make(LinkResource::from($link));
    }
}
