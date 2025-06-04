<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserDrafts;
use App\Actions\StoreDraft;
use App\Data\DraftFormData;
use App\Data\LinkData;
use App\Http\Requests\StoreDraftRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class DraftController
{
    public function index(
        #[CurrentUser] User $user,
        GetUserDrafts $getUserDrafts
    ): Response {
        return Inertia::render('drafts/index', [
            'drafts' => Inertia::deepMerge(
                LinkData::collect(
                    $getUserDrafts->execute($user)
                )
            ),
        ]);
    }

    public function store(StoreDraftRequest $request, #[CurrentUser] User $user, StoreDraft $storeDraft): FoundationResponse
    {
        $draft = $storeDraft->execute($user, DraftFormData::from($request));

        return Inertia::location(route('drafts.index'));
    }
}
