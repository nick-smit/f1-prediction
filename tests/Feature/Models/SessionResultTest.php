<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use App\Models\RaceSession;
use App\Models\SessionResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SessionResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_session_result_can_be_created(): void
    {
        $drivers = Driver::factory()->count(10)->create();
        $session = RaceSession::factory()->create();

        $result = new SessionResult();

        $result->raceSession()->associate($session);
        $result->p1()->associate($drivers[0]);
        $result->p2()->associate($drivers[1]);
        $result->p3()->associate($drivers[2]);
        $result->p4()->associate($drivers[3]);
        $result->p5()->associate($drivers[4]);
        $result->p6()->associate($drivers[5]);
        $result->p7()->associate($drivers[6]);
        $result->p8()->associate($drivers[7]);
        $result->p9()->associate($drivers[8]);
        $result->p10()->associate($drivers[9]);

        $success = $result->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(SessionResult::class, 1);
    }
}
