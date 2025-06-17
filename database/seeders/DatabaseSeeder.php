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
        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        $tags = Tag::factory(10)->for($user)->create();

        Collection::times(
            20,
            fn () => Link::factory()
                ->recycle($user)
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
