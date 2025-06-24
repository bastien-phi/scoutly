<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DraftFormData extends Data
{
    /**
     * @param  Collection<int, string>  $tags
     */
    public function __construct(
        public string $url,
        public ?string $title,
        public ?string $description,
        public bool $is_public,
        public ?string $author,
        #[LiteralTypeScriptType('string[]')]
        public Collection $tags,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromMailIngest(array $data): self
    {
        return self::factory()->withoutMagicalCreation()->from([
            'is_public' => false,
            'tags' => new Collection,
            ...$data,
        ]);
    }
}
