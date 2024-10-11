<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::factory()
            ->createMany([
                ['name' => 'Oracle Red Bull Racing'],
                ['name' => 'McLaren Formula 1 Team'],
                ['name' => 'Aston Martin Aramco Formula One Team'],
                ['name' => 'Scuderia Ferrari'],
                ['name' => 'MoneyGram Haas F1 Team'],
                ['name' => 'Scuderia AlphaTauri RB'],
                ['name' => 'BWT Alpine F1 Team'],
                ['name' => 'Williams Racing'],
                ['name' => 'Mercedes-AMG PETRONAS Formula One Team'],
                ['name' => 'Stake F1 Team Kick Sauber'],
            ]);

        $drivers = Driver::factory(20)->sequence(
            ['number' => 1, 'name' => 'Max Verstappen'],
            ['number' => 11, 'name' => 'Sergio Perez'],
            ['number' => 4, 'name' => 'Lando Norris'],
            ['number' => 81, 'name' => 'Oscar Piastri'],
            ['number' => 14, 'name' => 'Fernando Alonso'],
            ['number' => 18, 'name' => 'Lance Stroll'],
            ['number' => 16, 'name' => 'Charles Leclerc'],
            ['number' => 55, 'name' => 'Carlos Sainz'],
            ['number' => 20, 'name' => 'Kevin Magnussen'],
            ['number' => 27, 'name' => 'Nico Hulkenberg'],
            ['number' => 3, 'name' => 'Daniel Ricciardo'],
            ['number' => 22, 'name' => 'Yuki Tsunoda'],
            ['number' => 10, 'name' => 'Pierre Gasly'],
            ['number' => 31, 'name' => 'Esteban Ocon'],
            ['number' => 23, 'name' => 'Alexander Albon'],
            ['number' => 43, 'name' => 'Franco Colapinto'],
            ['number' => 44, 'name' => 'Lewis Hamilton'],
            ['number' => 63, 'name' => 'George Russell'],
            ['number' => 24, 'name' => 'Zhou Guanyu'],
            ['number' => 77, 'name' => 'Valtteri Bottas'],
        )->create();

        foreach ($drivers as $index => $driver) {
            DriverContract::factory()
                ->create([
                    'start_date' => Carbon::create(2024),
                    'team_id' => $teams->get(floor($index / 2))->getKey(),
                    'driver_id' => $driver->getKey(),
                ]);
        }
    }
}
