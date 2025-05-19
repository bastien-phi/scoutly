<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserLinks;
use App\Data\LinkData;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Inertia\Inertia;
use Inertia\Response;

class LinkController
{
    public function index(
        #[CurrentUser] User $user,
        GetUserLinks $getUserLinks
    ): Response {
        return Inertia::render('links/index', [
            'links' => Inertia::deepMerge(
                LinkData::collect(
                    $getUserLinks->execute($user)
                )
            ),
        ]);
    }
}
