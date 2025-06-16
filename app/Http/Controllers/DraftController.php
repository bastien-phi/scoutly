<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserDrafts;
use App\Actions\StoreDraft;
use App\Actions\UpdateDraft;
use App\Data\DraftFormData;
use App\Data\LinkData;
use App\Http\Requests\StoreDraftRequest;
use App\Models\Author;
use App\Models\Link;
use App\Models\Tag;
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

    public function edit(Link $draft): Response
    {
        $draft->load(['author', 'tags']);

        return Inertia::render('drafts/edit', [
            'draft' => LinkData::from($draft),
            'authors' => Author::query()
                ->orderBy('name')
                ->pluck('name'),
            'tags' => Tag::query()
                ->orderBy('label')
                ->pluck('label'),
        ]);
    }

    public function update(StoreDraftRequest $request, Link $draft, UpdateDraft $updateDraft): FoundationResponse
    {
        $updateDraft->execute($draft, DraftFormData::from($request));

        return to_route('drafts.edit', $draft);
    }
}
