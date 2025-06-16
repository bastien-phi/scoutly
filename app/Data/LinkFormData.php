<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LinkFormData extends Data
{
    /**
     * @param  Collection<int, string>  $tags
     */
    public function __construct(
        public string $url,
        public string $title,
        public ?string $description,
        public ?string $author,
        #[LiteralTypeScriptType('Array<string>')]
        public Collection $tags,
    ) {}
}
