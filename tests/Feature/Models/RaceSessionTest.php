<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\RaceSession;
use App\Models\RaceWeekend;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RaceSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_race_session_can_be_persisted(): void
    {
        /** @var RaceSession $raceSession */
        $raceSession = RaceSession::factory()->make();

        $success = $raceSession->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(RaceSession::class, 1);
        $this->assertDatabaseCount(RaceWeekend::class, 1);
        $this->assertInstanceOf(RaceWeekend::class, $raceSession->raceWeekend);
    }
}
