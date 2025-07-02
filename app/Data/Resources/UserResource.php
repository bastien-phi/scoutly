<?php

declare(strict_types=1);

namespace App\Data\Resources;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserResource extends Resource
{
    public function __construct(
        public string $uuid,
        public string $username,
    ) {}
}
