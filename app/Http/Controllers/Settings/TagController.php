<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\UpdateTag;
use App\Data\Requests\UpdateTagRequest;
use App\Data\Resources\TagSettingResource;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TagController
{
    public function index(#[CurrentUser] User $user): Response
    {
        $tags = $user->tags()
            ->withCount('links')
            ->orderBy('label')
            ->get();

        return Inertia::render('settings/tags', [
            'tags' => TagSettingResource::collect($tags),
        ]);
    }

    public function update(UpdateTagRequest $request, Tag $tag, UpdateTag $updateTag): RedirectResponse
    {
        $updateTag->execute($tag, $request);

        return redirect()->back();
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->back();
    }
}
