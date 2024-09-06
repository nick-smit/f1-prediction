<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::factory()
            ->count(10)
            ->sequence(
                ['name' => 'Oracle Red Bull Racing'],
                ['name' => 'Mercedes-AMG PETRONAS Formula One Team'],
                ['name' => 'Scuderia Ferrari'],
                ['name' => 'McLaren Formula 1 Team'],
                ['name' => 'Aston Martin Aramco Formula One Team'],
                ['name' => 'BWT Alpine F1 Team'],
                ['name' => 'Williams Racing'],
                ['name' => 'Scuderia AlphaTauri RB'],
                ['name' => 'Stake F1 Team Kick Sauber'],
                ['name' => 'MoneyGram Haas F1 Team'],
            )->create();
    }
}
