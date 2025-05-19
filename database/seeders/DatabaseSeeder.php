<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()
            ->hasLinks(10)
            ->create([
                'name' => 'Test User',
                'username' => 'testuser',
                'email' => 'test@example.com',
            ]);
    }
}
