<?php

declare(strict_types=1);

namespace App\Data\Requests;

use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class StoreLinkRequest extends Data
{
    /**
     * @param  ?array<int, string>  $tags
     */
    public function __construct(
        public string $url,
        public string $title,
        public ?string $description,
        public bool $is_public,
        public ?string $author,
        #[LiteralTypeScriptType('string[]')]
        public ?array $tags,
    ) {}

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'url' => ['url'],
            'title' => ['max:255'],
            'author' => ['max:255'],
            'tags.*' => ['required', 'string', 'max:255'],
        ];
    }
}
