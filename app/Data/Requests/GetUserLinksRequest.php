<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Data\Concerns\IncludesOnlyFilledValues;
use App\Models\Author;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class GetUserLinksRequest extends Data
{
    use IncludesOnlyFilledValues;

    /**
     * @param  ?array<int, string>  $tag_uuids
     */
    public function __construct(
        public ?string $search,
        public ?string $author_uuid,
        #[LiteralTypeScriptType('string[]')]
        public ?array $tag_uuids,
    ) {}

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, mixed>|string>
     */
    public static function rules(#[CurrentUser] User $user): array
    {
        return [
            'search' => ['max:255'],
            'author_uuid' => ['uuid', Rule::exists(Author::class, 'uuid')->where('user_id', $user->id)],
            'tag_uuids.*' => ['uuid', Rule::exists('tags', 'uuid')->where('user_id', $user->id)],
        ];
    }
}
