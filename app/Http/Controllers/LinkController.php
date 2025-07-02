<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserLinks;
use App\Actions\StoreLink;
use App\Actions\UpdateLink;
use App\Data\Requests\GetUserLinksRequest;
use App\Data\Requests\StoreLinkRequest;
use App\Data\Resources\AuthorResource;
use App\Data\Resources\LinkResource;
use App\Data\Resources\TagResource;
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
        GetUserLinksRequest $data,
        #[CurrentUser] User $user,
        GetUserLinks $getUserLinks
    ): Response {
        $links = $getUserLinks->execute($user, $data);

        return Inertia::render('links/index', [
            'request' => $data->onlyNotNull(),
            'links' => $links->currentPage() === 1 ? LinkResource::collect($links) : Inertia::deepMerge(LinkResource::collect($links)),
            'authors' => fn (): Collection => AuthorResource::collect($user->authors()->orderBy('name')->get()),
            'tags' => fn (): Collection => TagResource::collect($user->tags()->orderBy('label')->get()),
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

    public function store(StoreLinkRequest $data, #[CurrentUser] User $user, StoreLink $storeLink): FoundationResponse
    {
        $link = $storeLink->execute($user, $data);

        return to_route('links.show', $link);
    }

    public function show(Link $link): Response
    {
        $link->load(['author', 'tags']);

        return Inertia::render('links/show', [
            'link' => LinkResource::from($link),
        ]);
    }

    public function edit(#[CurrentUser] User $user, Link $link): Response
    {
        $link->load(['author', 'tags']);

        return Inertia::render('links/edit', [
            'link' => LinkResource::from($link),
            'authors' => $user->authors()
                ->orderBy('name')
                ->pluck('name'),
            'tags' => $user->tags()
                ->orderBy('label')
                ->pluck('label'),
        ]);
    }

    public function update(StoreLinkRequest $data, Link $link, UpdateLink $updateLink): FoundationResponse
    {
        $updateLink->execute($link, $data);

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
