<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LinkData extends Data
{
    /**
     * @param  Collection<int, TagData>  $tags
     */
    public function __construct(
        public int $id,
        public string $url,
        public ?string $title,
        public ?string $description,
        public ?CarbonImmutable $published_at,
        public CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
        public ?AuthorData $author,
        #[LiteralTypeScriptType('Array<App.Data.TagData>')]
        public Collection $tags,
    ) {}
}
