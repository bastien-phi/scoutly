<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Concerns\IncludesOnlyFilledValues;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SearchCommunityLinkFormData extends Data
{
    use IncludesOnlyFilledValues;

    /**
     * @param  Collection<int, string>  $tags
     */
    public function __construct(
        public ?string $search,
        public ?string $author,
        #[LiteralTypeScriptType('string[]')]
        public Collection $tags = new Collection
    ) {}
}
