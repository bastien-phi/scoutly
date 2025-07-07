<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class LinkMetaData extends Data
{
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $image,
        public ?string $html,
    ) {}
}
