<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Concerns\IncludesOnlyFilledValues;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class GetCommunityLinksRequest extends Data
{
    use IncludesOnlyFilledValues;

    /**
     * @param  ?array<int, string>  $tags
     */
    public function __construct(
        public ?string $search,
        public ?string $author,
        #[LiteralTypeScriptType('string[]')]
        public ?array $tags,
        public ?string $user,
    ) {}

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'search' => ['max:255'],
            'author' => ['max:255'],
            'tags.*' => ['required', 'string', 'max:255'],
            'user' => ['uuid'],
        ];
    }
}
