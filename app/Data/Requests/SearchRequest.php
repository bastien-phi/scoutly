<?php

declare(strict_types=1);

namespace App\Data\Requests;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class SearchRequest extends Data
{
    public function __construct(
        #[Max(255)]
        public ?string $search = null,
    ) {}
}
