<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Author;
use App\Models\User;

class FindOrCreateAuthor
{
    public function execute(User $user, ?string $author): ?Author
    {
        if ($author === null) {
            return null;
        }

        return $user->authors()->firstOrCreate(['name' => $author]);
    }
}
