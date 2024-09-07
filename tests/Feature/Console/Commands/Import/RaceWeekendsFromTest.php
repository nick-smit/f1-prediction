<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Import;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RaceWeekendsFromTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_race_weekends_from_ics_script(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'files-f1.motorsportcalendars.com/*' => \Illuminate\Support\Facades\Http::response(\Illuminate\Support\Facades\File::get(storage_path('testing/f1-calendar_p1_p2_p3_qualifying_sprint_gp.ics'))),
        ]);

        $result = $this->artisan('import:race-weekends-from-ics');

        $result->assertSuccessful();
        $result->expectsOutput('Importing 24 race weekends');
        $result->expectsOutput('Imported 120 race sessions');
    }
}
