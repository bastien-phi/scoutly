<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Author;
use Illuminate\Support\Str;

class FindOrCreateAuthor
{
    public function execute(?string $author): ?Author
    {
        if ($author === null) {
            return null;
        }

        return Author::query()
            ->whereRaw('LOWER(name) = ?', Str::lower($author))
            ->firstOr(
                fn () => Author::query()->create(['name' => $author])
            );
    }
}
