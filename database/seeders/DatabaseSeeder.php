<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            User::factory(10)->create();

            User::factory()->admin()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'test'
            ]);

            $this->call(ParticipantsSeeder::class);
            $this->call(Season2024WeekendSeeder::class);
        }
    }
}
