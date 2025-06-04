<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Link;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LinkPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Link $link): Response
    {
        if ($link->published_at === null) {
            return Response::denyAsNotFound();
        }

        if ($user->id !== $link->user_id) {
            return Response::denyAsNotFound();
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Link $link): Response
    {
        if ($link->published_at === null) {
            return Response::denyAsNotFound();
        }

        if ($user->id !== $link->user_id) {
            return Response::denyAsNotFound();
        }

        return Response::allow();
    }

    public function updateDraft(User $user, Link $draft): Response
    {
        if ($draft->published_at !== null) {
            return Response::denyAsNotFound();
        }

        if ($user->id !== $draft->user_id) {
            return Response::denyAsNotFound();
        }

        return Response::allow();
    }

    public function delete(User $user, Link $link): Response
    {
        if ($user->id !== $link->user_id) {
            return Response::denyAsNotFound();
        }

        return Response::allow();
    }
}
