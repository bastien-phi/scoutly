<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\LinkData;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Inertia\Inertia;
use Inertia\Response;

class LinkController
{
    public function index(#[CurrentUser] User $user): Response
    {
        return Inertia::render('links/index', [
            'links' => Inertia::deepMerge(
                LinkData::collect(
                    $user->links()
                        ->with('author')
                        ->latest('published_at')
                        ->paginate()
                )
            ),
        ]);
    }
}
