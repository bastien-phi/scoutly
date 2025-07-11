<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Author;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @method \Database\Factories\LinkFactory forAuthor($attributes = [])
 * @method \Database\Factories\LinkFactory forUser($attributes = [])
 * @method \Database\Factories\LinkFactory hasTags($count = 1, $attributes = [])
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'author_id' => fn (array $attributes) => Author::factory(['user_id' => $attributes['user_id']]),
            'title' => fake()->sentence(),
            'url' => fake()->url(),
            'description' => fake()->paragraph(),
            'published_at' => fake()->optional(0.8)->dateTime(),
            'is_public' => fake()->boolean(),
        ];
    }

    public function draft(): static
    {
        return $this->state([
            'published_at' => null,
        ]);
    }

    public function published(?CarbonImmutable $publishedAt = null): static
    {
        return $this->state([
            'published_at' => $publishedAt ?? fn () => fake()->dateTime(),
        ]);
    }

    public function isPublic(): static
    {
        return $this->state([
            'is_public' => true,
        ]);
    }

    public function isPrivate(): static
    {
        return $this->state([
            'is_public' => false,
        ]);
    }
}
