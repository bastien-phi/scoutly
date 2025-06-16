<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        Collection::times(
            20,
            fn () => Link::factory()
                ->for($user)
                ->recycle($tags)
                ->hasAttached(
                    $tags->random(
                        fake()->optional(weight: 0.8, default: 0)
                            ->numberBetween(1, 5)
                    )
                )
                ->createOne()
        );
    }
}
