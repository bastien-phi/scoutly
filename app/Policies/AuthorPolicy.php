<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Author;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AuthorPolicy
{
    public function update(User $user, Author $author): Response
    {
        return $author->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Author $author): Response
    {
        return $author->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
