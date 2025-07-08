<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    public function update(User $user, Tag $tag): Response
    {
        return $tag->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Tag $tag): Response
    {
        return $tag->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
