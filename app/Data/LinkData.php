<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LinkData extends Data
{
    public function __construct(
        public int $id,
        public string $url,
        public ?string $title,
        public ?string $description,
        public ?CarbonImmutable $published_at,
        public CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
        public ?AuthorData $author,
    ) {}
}
