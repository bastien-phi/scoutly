<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Resources\AuthorResource;
use App\Data\Resources\TagResource;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommunityLinkData extends Data
{
    /**
     * @param  Collection<int, TagResource>  $tags
     */
    public function __construct(
        public string $uuid,
        public string $url,
        public ?string $title,
        public ?string $description,
        public ?CarbonImmutable $published_at,
        public UserData $user,
        public ?AuthorResource $author,
        #[LiteralTypeScriptType('App.Data.TagData[]')]
        public Collection $tags,
    ) {}
}
