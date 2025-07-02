<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Data\Resources\LinkResource;
use App\Http\Resources\DataResource;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

class RandomLinkController
{
    public function __invoke(#[CurrentUser] User $user): DataResource
    {
        $link = $user->links()
            ->wherePublished()
            ->with(['author', 'tags'])
            ->random();

        return DataResource::make($link, LinkResource::class);
    }
}
