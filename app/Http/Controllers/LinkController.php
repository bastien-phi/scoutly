<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserLinks;
use App\Actions\StoreLink;
use App\Actions\UpdateLink;
use App\Data\AuthorData;
use App\Data\LinkData;
use App\Data\LinkFormData;
use App\Data\SearchLinkFormData;
use App\Data\TagData;
use App\Http\Requests\GetUserLinksRequest;
use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class LinkController
{
    public function index(
        GetUserLinksRequest $request,
        #[CurrentUser] User $user,
        GetUserLinks $getUserLinks
    ): Response {
        $data = SearchLinkFormData::from($request);
        $links = $getUserLinks->execute($user, $data);

        return Inertia::render('links/index', [
            'request' => $data->onlyNotNull(),
            'links' => $links->currentPage() === 1 ? LinkData::collect($links) : Inertia::deepMerge(LinkData::collect($links)),
            'authors' => fn (): Collection => AuthorData::collect($user->authors()->orderBy('name')->get()),
            'tags' => fn (): Collection => TagData::collect($user->tags()->orderBy('label')->get()),
        ]);
    }

    public function create(#[CurrentUser] User $user): Response
    {
        return Inertia::render('links/create', [
            'authors' => $user->authors()
                ->orderBy('name')
                ->pluck('name'),
            'tags' => $user->tags()
                ->orderBy('label')
                ->pluck('label'),
        ]);
    }

    public function store(StoreLinkRequest $request, #[CurrentUser] User $user, StoreLink $storeLink): FoundationResponse
    {
        $link = $storeLink->execute($user, LinkFormData::from($request));

        return to_route('links.show', $link);
    }

    public function show(Link $link): Response
    {
        $link->load(['author', 'tags']);

        return Inertia::render('links/show', [
            'link' => LinkData::from($link),
        ]);
    }

    public function edit(#[CurrentUser] User $user, Link $link): Response
    {
        $link->load(['author', 'tags']);

        return Inertia::render('links/edit', [
            'link' => LinkData::from($link),
            'authors' => $user->authors()
                ->orderBy('name')
                ->pluck('name'),
            'tags' => $user->tags()
                ->orderBy('label')
                ->pluck('label'),
        ]);
    }

    public function update(StoreLinkRequest $request, Link $link, UpdateLink $updateLink): FoundationResponse
    {
        $updateLink->execute($link, LinkFormData::from($request));

        return to_route('links.show', $link);
    }

    public function destroy(Link $link): FoundationResponse
    {
        $link->delete();

        return to_route(
            $link->published_at === null ?
            'drafts.index' :
            'links.index'
        );
    }
}
