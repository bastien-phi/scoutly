<?php

declare(strict_types=1);

namespace App\Data\Requests;

use App\Models\Author;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class UpdateAuthorRequest extends Data
{
    public function __construct(
        public string $name,
    ) {}

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, mixed>|string>
     */
    public static function rules(
        #[CurrentUser] User $user,
        #[RouteParameter('author')] Author $author
    ): array {
        return [
            'name' => [
                'max:255',
                Rule::unique(Author::class, 'name')
                    ->where('user_id', $user->id)
                    ->ignore($author->id),
            ],
        ];
    }
}
