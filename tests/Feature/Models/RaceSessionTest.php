<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Prediction;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use App\Models\SessionResult;
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

    public function test_a_race_session_can_have_a_result(): void
    {
        $raceSession = RaceSession::factory()->create();
        $result = SessionResult::factory(['race_session_id' => null])->make();

        $raceSession->sessionResult()->save($result);

        $raceSession = $raceSession->refresh();

        $this->assertDatabaseCount(SessionResult::class, 1);
        $this->assertNotNull($raceSession->sessionResult);
    }

    public function test_a_race_session_can_have_predictions(): void
    {
        $raceSession = RaceSession::factory()->create();
        $predictions = Prediction::factory()->count(10)->make(['race_session_id' => null]);

        $raceSession->predictions()->saveMany($predictions);

        $raceSession = $raceSession->refresh();

        $this->assertDatabaseCount(Prediction::class, 10);
        $this->assertNotEmpty($raceSession->predictions->toArray());
    }
}
