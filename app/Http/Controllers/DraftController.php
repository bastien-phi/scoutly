<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserDrafts;
use App\Actions\StoreDraft;
use App\Actions\UpdateLink;
use App\Data\DraftFormData;
use App\Data\LinkData;
use App\Data\ToastData;
use App\Http\Requests\StoreDraftRequest;
use App\Models\Link;
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
            'draftEmail' => config('imap.mailboxes.default.username'),
        ]);
    }

    public function store(StoreDraftRequest $request, #[CurrentUser] User $user, StoreDraft $storeDraft): FoundationResponse
    {
        $draft = $storeDraft->execute($user, DraftFormData::from($request));

        return to_route('drafts.edit', $draft);
    }

    public function edit(#[CurrentUser] User $user, Link $draft): Response
    {
        $draft->load(['author', 'tags']);

        return Inertia::render('drafts/edit', [
            'draft' => LinkData::from($draft),
            'authors' => $user->authors()
                ->orderBy('name')
                ->pluck('name'),
            'tags' => $user->tags()
                ->orderBy('label')
                ->pluck('label'),
        ]);
    }

    public function update(StoreDraftRequest $request, Link $draft, UpdateLink $updateLink): FoundationResponse
    {
        $updateLink->execute($draft, DraftFormData::from($request));

        return to_route('drafts.edit', $draft)->with([
            'toast' => ToastData::success('Draft saved'),
        ]);
    }
}
