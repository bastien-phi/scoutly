<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommunityLinkData extends Data
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
        public UserData $user,
        public ?AuthorData $author,
        #[LiteralTypeScriptType('App.Data.TagData[]')]
        public Collection $tags,
    ) {}
}
