<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\UpdateAuthor;
use App\Data\Requests\UpdateAuthorRequest;
use App\Data\Resources\AuthorSettingResource;
use App\Models\Author;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AuthorController
{
    public function index(#[CurrentUser] User $user): Response
    {
        $authors = $user->authors()
            ->withCount('links')
            ->orderBy('name')
            ->get();

        return Inertia::render('settings/authors', [
            'authors' => AuthorSettingResource::collect($authors),
        ]);
    }

    public function update(UpdateAuthorRequest $request, Author $author, UpdateAuthor $updateAuthor): RedirectResponse
    {
        $updateAuthor->execute($author, $request);

        return redirect()->back();
    }

    public function destroy(Author $author): RedirectResponse
    {
        $author->delete();

        return redirect()->back();
    }
}
