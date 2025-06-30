<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetUserLinksRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<int, mixed>|string>
     */
    public function rules(#[CurrentUser] User $user): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'author_uuid' => ['nullable', 'uuid', Rule::exists(Author::class, 'uuid')->where('user_id', $user->id)],
            'tag_uuids' => ['array'],
            'tag_uuids.*' => ['uuid', Rule::exists(Tag::class, 'uuid')->where('user_id', $user->id)],
        ];
    }
}
