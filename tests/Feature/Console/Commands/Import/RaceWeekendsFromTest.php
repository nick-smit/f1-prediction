<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Import;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class RaceWeekendsFromTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_race_weekends_from_ics_script(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'files-f1.motorsportcalendars.com/*' => Http::response(Storage::get('console/commands/import/f1-calendar_p1_p2_p3_qualifying_sprint_gp.ics')),
        ]);

        $result = $this->artisan('import:race-weekends-from-ics');

        $result->expectsQuestion('What is the stats f1 url for the Bahrain Grand Prix', 'https://www.statsf1.com/en/2024/bahrein.aspx');
        $result->expectsQuestion('What is the stats f1 url for the Saudi Arabian Grand Prix', 'https://www.statsf1.com/en/2024/arabie-saoudite.aspx');

        $result->assertSuccessful();
        $result->expectsOutput('Importing 2 race weekends');
        $result->expectsOutput('Imported 4 race sessions');
    }
}
