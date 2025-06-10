<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Concerns\IncludesOnlyFilledValues;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SearchLinkFormData extends Data
{
    use IncludesOnlyFilledValues;

    public function __construct(
        public ?string $search,
        public ?int $author_id,
    ) {}
}
