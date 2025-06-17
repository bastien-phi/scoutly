<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Author;
use App\Models\User;
use Illuminate\Support\Str;

class FindOrCreateAuthor
{
    public function execute(User $user, ?string $author): ?Author
    {
        if ($author === null) {
            return null;
        }

        return $user->authors()
            ->whereRaw('LOWER(name) = ?', Str::lower($author))
            ->firstOr(
                fn () => $user->authors()->create(['name' => $author])
            );
    }
}
