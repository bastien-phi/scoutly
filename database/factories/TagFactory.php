<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @method \Database\Factories\TagFactory forUser($attributes = [])
 * @method \Database\Factories\TagFactory hasLinks($count = 1, $attributes = [])
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->unique()->words(
                nb: fake()->numberBetween(1, 3),
                asText: true
            ),
        ];
    }
}
