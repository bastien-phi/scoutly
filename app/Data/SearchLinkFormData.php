<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Concerns\IncludesOnlyFilledValues;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SearchLinkFormData extends Data
{
    use IncludesOnlyFilledValues;

    /**
     * @param  Collection<int, int>  $tags
     */
    public function __construct(
        public ?string $search,
        public ?int $author_id,
        #[LiteralTypeScriptType('number[]')]
        public Collection $tags = new Collection,
    ) {}
}
