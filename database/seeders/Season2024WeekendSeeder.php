<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Console\Commands\Import\RaceWeekendsFromIcs;
use App\Models\RaceWeekend;
use Illuminate\Database\Seeder;

class Season2024WeekendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RaceWeekend::factory()
            ->createMany([
                ['start_date' => '2024-02-29', 'name' => 'Bahrain Grand Prix', 'stats_f1_name' => 'bahrein'],
                ['start_date' => '2024-03-07', 'name' => 'Saudi Arabian Grand Prix', 'stats_f1_name' => 'arabie-saoudite'],
                ['start_date' => '2024-03-22', 'name' => 'Australian Grand Prix', 'stats_f1_name' => 'australie'],
                ['start_date' => '2024-04-05', 'name' => 'Japanese Grand Prix', 'stats_f1_name' => 'japon'],
                ['start_date' => '2024-04-19', 'name' => 'Chinese Grand Prix', 'stats_f1_name' => 'chine'],
                ['start_date' => '2024-05-03', 'name' => 'Miami Grand Prix', 'stats_f1_name' => 'miami'],
                ['start_date' => '2024-05-17', 'name' => 'Emilia Romagna Grand Prix', 'stats_f1_name' => 'emilie-romagne'],
                ['start_date' => '2024-05-24', 'name' => 'Monaco Grand Prix', 'stats_f1_name' => 'monaco'],
                ['start_date' => '2024-06-07', 'name' => 'Canadian Grand Prix', 'stats_f1_name' => 'canada'],
                ['start_date' => '2024-06-21', 'name' => 'Spanish Grand Prix', 'stats_f1_name' => 'espagne'],
                ['start_date' => '2024-06-28', 'name' => 'Austrian Grand Prix', 'stats_f1_name' => 'autriche'],
                ['start_date' => '2024-07-05', 'name' => 'British Grand Prix', 'stats_f1_name' => 'grande-bretagne'],
                ['start_date' => '2024-07-19', 'name' => 'Hungarian Grand Prix', 'stats_f1_name' => 'hongrie'],
                ['start_date' => '2024-07-26', 'name' => 'Belgian Grand Prix', 'stats_f1_name' => 'belgique'],
                ['start_date' => '2024-08-23', 'name' => 'Dutch Grand Prix', 'stats_f1_name' => 'pays-bas'],
                ['start_date' => '2024-08-30', 'name' => 'Italian Grand Prix', 'stats_f1_name' => 'italie'],
                ['start_date' => '2024-09-13', 'name' => 'Azerbaijan Grand Prix', 'stats_f1_name' => 'azerbaidjan'],
                ['start_date' => '2024-09-20', 'name' => 'Singapore Grand Prix', 'stats_f1_name' => 'singapour'],
                ['start_date' => '2024-10-18', 'name' => 'United States Grand Prix', 'stats_f1_name' => 'etats-unis'],
                ['start_date' => '2024-10-25', 'name' => 'Mexico City Grand Prix', 'stats_f1_name' => 'mexico-city'],
                ['start_date' => '2024-11-01', 'name' => 'Brazilian Grand Prix', 'stats_f1_name' => 'sao-paulo'],
                ['start_date' => '2024-11-22', 'name' => 'Las Vegas Grand Prix', 'stats_f1_name' => 'las-vegas'],
                ['start_date' => '2024-11-29', 'name' => 'Qatar Grand Prix', 'stats_f1_name' => 'qatar'],
                ['start_date' => '2024-12-06', 'name' => 'Abu Dhabi Grand Prix', 'stats_f1_name' => 'abou-dhabi'],
            ]);

        $this->command->info('Importing race weekends from ICS');
        $this->command->call(RaceWeekendsFromIcs::class);
    }
}
