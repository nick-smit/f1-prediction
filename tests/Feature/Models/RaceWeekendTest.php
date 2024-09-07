<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\GrandPrixGuessr\Session\SessionType;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RaceWeekendTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_race_weekend_can_be_persisted(): void
    {
        /** @var RaceWeekend $raceWeekend */
        $raceWeekend = RaceWeekend::factory()->make();

        $success = $raceWeekend->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(RaceWeekend::class, 1);
    }

    public function test_a_race_weekend_can_have_race_sessions(): void
    {
        $raceWeekend = RaceWeekend::factory()->create();

        $sessions = RaceSession::factory()
            ->state(['race_weekend_id' => null])
            ->count(2)
            ->sequence(['type' => SessionType::Qualification], ['type' => SessionType::Race])
            ->make();

        $raceWeekend->raceSessions()->saveMany($sessions);

        $this->assertDatabaseCount(RaceSession::class, 2);
    }
}
