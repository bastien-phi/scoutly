<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class UpdateTagRequest extends Data
{
    public function __construct(
        public string $label,
    ) {}

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, mixed>|string>
     */
    public static function rules(
        #[CurrentUser] User $user,
        #[RouteParameter('tag')] Tag $tag
    ): array {
        return [
            'label' => [
                'max:255',
                Rule::unique(Tag::class, 'label')
                    ->where('user_id', $user->id)
                    ->ignore($tag->id),
            ],
        ];
    }
}
