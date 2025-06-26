<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TagStatisticData extends Data
{
    public function __construct(
        public int $id,
        public string $label,
        public int $links_count,
    ) {}
}
