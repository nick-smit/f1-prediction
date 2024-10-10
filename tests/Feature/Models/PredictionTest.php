<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use App\Models\Prediction;
use App\Models\RaceSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PredictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_prediction_can_be_created(): void
    {
        $user = User::factory()->create();
        $drivers = Driver::factory()->count(10)->create();
        $session = RaceSession::factory()->create();

        $prediction = new Prediction();

        $prediction->user()->associate($user);
        $prediction->raceSession()->associate($session);
        $prediction->p1()->associate($drivers[0]);
        $prediction->p2()->associate($drivers[1]);
        $prediction->p3()->associate($drivers[2]);
        $prediction->p4()->associate($drivers[3]);
        $prediction->p5()->associate($drivers[4]);
        $prediction->p6()->associate($drivers[5]);
        $prediction->p7()->associate($drivers[6]);
        $prediction->p8()->associate($drivers[7]);
        $prediction->p9()->associate($drivers[8]);
        $prediction->p10()->associate($drivers[9]);

        $success = $prediction->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(Prediction::class, 1);
    }

    public function test_a_prediction_can_retrieve_a_collection_of_drivers(): void
    {
        $prediction = Prediction::factory()->create();

        $driversCollection = $prediction->getDrivers();
        $this->assertCount(10, $driversCollection);
        $this->assertContainsEquals($prediction->p1, $driversCollection);
        $this->assertContainsEquals($prediction->p2, $driversCollection);
        $this->assertContainsEquals($prediction->p3, $driversCollection);
        $this->assertContainsEquals($prediction->p4, $driversCollection);
        $this->assertContainsEquals($prediction->p5, $driversCollection);
        $this->assertContainsEquals($prediction->p6, $driversCollection);
        $this->assertContainsEquals($prediction->p7, $driversCollection);
        $this->assertContainsEquals($prediction->p8, $driversCollection);
        $this->assertContainsEquals($prediction->p9, $driversCollection);
        $this->assertContainsEquals($prediction->p10, $driversCollection);
    }
}
