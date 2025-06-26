<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

class LinkCountController
{
    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $count = $user->links()
            ->wherePublished()
            ->count();

        return new JsonResponse(['data' => $count]);
    }
}
