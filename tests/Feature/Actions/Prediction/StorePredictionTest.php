<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Prediction;

use App\Actions\Prediction\StorePrediction;
use App\Models\Driver;
use App\Models\Prediction;
use App\Models\RaceSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Metadata\CoversClass;
use Tests\TestCase;

#[CoversClass(StorePrediction::class)]
final class StorePredictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_new_prediction(): void
    {
        $user = User::factory()->create();
        $raceSession = RaceSession::factory()->create(['session_start' => Carbon::today()]);

        $drivers = Driver::factory()->count(10)->create();

        $action = $this->app->make(StorePrediction::class);

        $action->handle($user, $raceSession, $drivers->map(fn (Driver $driver) => $driver->id));

        $this->assertDatabaseHas(Prediction::class, [
            'user_id' => $user->id,
            'race_session_id' => $raceSession->id,
            'p1_id' => $drivers->get(0)->id,
            'p2_id' => $drivers->get(1)->id,
            'p3_id' => $drivers->get(2)->id,
            'p4_id' => $drivers->get(3)->id,
            'p5_id' => $drivers->get(4)->id,
            'p6_id' => $drivers->get(5)->id,
            'p7_id' => $drivers->get(6)->id,
            'p8_id' => $drivers->get(7)->id,
            'p9_id' => $drivers->get(8)->id,
            'p10_id' => $drivers->get(9)->id,
            'score' => null,
        ]);
    }

    public function test_it_updates_an_existing_prediction(): void
    {
        $prediction = Prediction::factory()->create();

        $drivers = (new Collection([
            $prediction->p1,
            $prediction->p2,
            $prediction->p3,
            $prediction->p4,
            $prediction->p5,
            $prediction->p6,
            $prediction->p7,
            $prediction->p8,
            $prediction->p9,
            $prediction->p10,
        ]))->shuffle();

        $action = $this->app->make(StorePrediction::class);

        $action->handle($prediction->user, $prediction->raceSession, $drivers->map(fn (Driver $driver) => $driver->id));

        $this->assertDatabaseHas(Prediction::class, [
            'user_id' => $prediction->user_id,
            'race_session_id' => $prediction->race_session_id,
            'p1_id' => $drivers->get(0)->id,
            'p2_id' => $drivers->get(1)->id,
            'p3_id' => $drivers->get(2)->id,
            'p4_id' => $drivers->get(3)->id,
            'p5_id' => $drivers->get(4)->id,
            'p6_id' => $drivers->get(5)->id,
            'p7_id' => $drivers->get(6)->id,
            'p8_id' => $drivers->get(7)->id,
            'p9_id' => $drivers->get(8)->id,
            'p10_id' => $drivers->get(9)->id,
            'score' => null,
        ]);
    }
}
