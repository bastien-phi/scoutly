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
     * @param  Collection<int, string>  $tag_uuids
     */
    public function __construct(
        public ?string $search,
        public ?string $author_uuid,
        #[LiteralTypeScriptType('string[]')]
        public Collection $tag_uuids = new Collection,
    ) {}
}
