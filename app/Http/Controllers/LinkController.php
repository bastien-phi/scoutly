<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserLinks;
use App\Actions\StoreLink;
use App\Actions\UpdateLink;
use App\Data\LinkData;
use App\Data\LinkFormData;
use App\Http\Requests\StoreLinkRequest;
use App\Models\Author;
use App\Models\Link;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

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

    public function create(): Response
    {
        return Inertia::render('links/create', [
            'authors' => Author::query()
                ->orderBy('name')
                ->pluck('name'),
        ]);
    }

    public function store(StoreLinkRequest $request, #[CurrentUser] User $user, StoreLink $storeLink): FoundationResponse
    {
        $link = $storeLink->execute($user, LinkFormData::from($request));

        return to_route('links.show', $link);
    }

    public function show(Link $link): Response
    {
        $link->load('author');

        return Inertia::render('links/show', [
            'link' => LinkData::from($link),
        ]);
    }

    public function edit(Link $link): Response
    {
        $link->load('author');

        return Inertia::render('links/edit', [
            'link' => LinkData::from($link),
            'authors' => Author::query()
                ->orderBy('name')
                ->pluck('name'),
        ]);
    }

    public function update(StoreLinkRequest $request, Link $link, UpdateLink $updateLink): FoundationResponse
    {
        $updateLink->execute($link, LinkFormData::from($request));

        return Inertia::location(route('links.show', $link));
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
