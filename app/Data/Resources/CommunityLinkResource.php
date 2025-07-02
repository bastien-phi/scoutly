<?php

declare(strict_types=1);

namespace App\Data\Resources;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommunityLinkResource extends Resource
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
        public UserResource $user,
        public ?AuthorResource $author,
        #[LiteralTypeScriptType('App.Data.Resources.TagResource[]')]
        public Collection $tags,
    ) {}
}
