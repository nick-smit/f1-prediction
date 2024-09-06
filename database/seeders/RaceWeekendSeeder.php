<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\RaceWeekend;
use DateTime;
use Illuminate\Database\Seeder;

class RaceWeekendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RaceWeekend::factory()
            ->count(24)
            ->sequence(
                ['start_date' => new DateTime('Feb 29 2024'), 'name' => 'Gulf Air Bahrain Grand Prix'],
                ['start_date' => new DateTime('Mar 7 2024'), 'name' => 'STC Saudi Arabian Grand Prix'],
                ['start_date' => new DateTime('Mar 22 2024'), 'name' => 'Rolex Australian Grand Prix'],
                ['start_date' => new DateTime('Apr 5 2024'), 'name' => 'MSC Cruises Japanese Grand Prix'],
                ['start_date' => new DateTime('Apr 19 2024'), 'name' => 'Lenovo Chinese Grand Prix'],
                ['start_date' => new DateTime('May 3 2024'), 'name' => 'Crypto.com Miami Grand Prix'],
                ['start_date' => new DateTime('May 17 2024'), 'name' => 'MSC Cruises Emilia Romagna Grand Prix'],
                ['start_date' => new DateTime('May 24 2024'), 'name' => 'Monaco Grand Prix'],
                ['start_date' => new DateTime('Jun 7 2024'), 'name' => 'AWS Canadian Grand Prix'],
                ['start_date' => new DateTime('Jun 21 2024'), 'name' => 'Aramco Spanish Grand Prix'],
                ['start_date' => new DateTime('Jun 28 2024'), 'name' => 'Qatar Airways Austrian Grand Prix'],
                ['start_date' => new DateTime('Jul 5 2024'), 'name' => 'Qatar Airways British Grand Prix'],
                ['start_date' => new DateTime('Jul 19 2024'), 'name' => 'Hungarian Grand Prix'],
                ['start_date' => new DateTime('Jul 26 2024'), 'name' => 'Rolex Belgian Grand Prix'],
                ['start_date' => new DateTime('Aug 23 2024'), 'name' => 'Heineken Dutch Grand Prix'],
                ['start_date' => new DateTime('Aug 30 2024'), 'name' => 'Pirelli Italian Grand Prix'],
                ['start_date' => new DateTime('Sep 13 2024'), 'name' => 'Qatar Airways Azerbaijan Grand Prix'],
                ['start_date' => new DateTime('Sep 20 2024'), 'name' => 'Singapore Airlines Singapore Grand Prix'],
                ['start_date' => new DateTime('Oct 18 2024'), 'name' => 'Pirelli United States Grand Prix'],
                ['start_date' => new DateTime('Oct 25 2024'), 'name' => 'Mexico City Grand Prix'],
                ['start_date' => new DateTime('Nov 1 2024'), 'name' => 'Lenovo São Paulo Grand Prix'],
                ['start_date' => new DateTime('Nov 22 2024'), 'name' => 'Heineken Las Vegas Grand Prix'],
                ['start_date' => new DateTime('Nov 29 2024'), 'name' => 'Qatar Airways Qatar Grand Prix'],
                ['start_date' => new DateTime('Dec 6 2024'), 'name' => 'Etihad Airways Abu Dhabi Grand Prix'],
            )
            ->create();
    }
}
