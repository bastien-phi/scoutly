<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\Link;
use Illuminate\Http\JsonResponse;

class CommunityLinkCountController
{
    public function __invoke(): JsonResponse
    {
        $count = Link::query()
            ->wherePublished()
            ->wherePublic()
            ->count();

        return new JsonResponse(['data' => $count]);
    }
}
