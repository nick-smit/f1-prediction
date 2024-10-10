<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\PredictionController;
use App\Http\Requests\Prediction\PredictionRequest;
use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Guess;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Iterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(PredictionController::class)]
#[CoversClass(PredictionRequest::class)]
final class PredictionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_predict_index_method_returns_a_view_without_an_event_if_there_is_none(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('prediction.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): AssertableJson => $page->component('Predict/Predict')
            ->where('event', null)
            ->where('drivers', null)
        );
    }

    public function test_the_predict_index_method_returns_a_view_with_the_latest_event(): void
    {
        $user = User::factory()->create();

        DriverContract::factory()->count(10)->create();

        RaceWeekend::factory()
            ->has(RaceSession::factory()->qualification()->state(['session_start' => Carbon::tomorrow()])->has(
                Guess::factory()->drivers(Driver::all())->state(['user_id' => $user])
            ))
            ->has(RaceSession::factory()->race()->state(['session_start' => Carbon::tomorrow()->addDay()]))
            ->create([
                'name' => 'Some GP'
            ]);

        RaceWeekend::factory()
            ->has(RaceSession::factory()->qualification()->state(['session_start' => Carbon::tomorrow()->addDays(7)]))
            ->has(RaceSession::factory()->race()->state(['session_start' => Carbon::tomorrow()->addDays(8)]))
            ->create([
                'name' => 'Other GP'
            ]);

        $response = $this->actingAs($user)->get(route('prediction.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): AssertableJson => $page->component('Predict/Predict')
            ->has(
                'event',
                fn (AssertableInertia $page): AssertableJson => $page->where('name', 'Some GP')
                ->whereType('qualification.id', 'integer')
                ->where('qualification.session_start', Carbon::tomorrow()->jsonSerialize())
                ->has('qualification.prediction', 10)
                ->whereType('race.id', 'integer')
                ->where('race.session_start', Carbon::tomorrow()->addDay()->jsonSerialize())
                ->where('race.prediction', [])
            )
            ->has('drivers', 10)
        );
    }

    public function test_a_prediction_can_be_stored(): void
    {
        $user = User::factory()->create();
        $session = RaceSession::factory()->create(['session_start' => Carbon::tomorrow()]);

        $drivers = Driver::factory()
            ->count(10)
            ->has(DriverContract::factory()->state(['start_date' => Carbon::yesterday()]), 'contracts')
            ->create();

        $response = $this->actingAs($user)->postJson(route('prediction.store', ['raceSession' => $session->id]), [
            'prediction' => $drivers->map(fn (Driver $d) => $d->id)->toArray(),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas(Guess::class, [
            'user_id' => $user->id,
            'race_session_id' => $session->id,
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

    #[DataProvider('validationProvider')]
    public function test_prediction_validation(callable $raceSession, array $data, array $expectedErrors): void
    {
        $user = User::factory()->create();
        $raceSession = $raceSession();

        $route = route('prediction.store', ['raceSession' => $raceSession->id]);

        foreach ($data as $key => $value) {
            if (is_callable($value)) {
                $data[$key] = $value();
            }
        }

        $response = $this->actingAs($user)->postJson($route, $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function validationProvider(): Iterator
    {
        yield 'The session start time must be in the past' => [
            fn () => RaceSession::factory()->create(['session_start' => Carbon::yesterday()]),
            [],
            ['prediction' => "It's not possible to make a prediciton anymore"]
        ];
        yield 'The prediction key is required' => [
            fn () => RaceSession::factory()->create(['session_start' => Carbon::tomorrow()]),
            ['prediction' => null],
            ['prediction' => 'The prediction field is required.']
        ];
        yield 'The prediction key must contain an array' => [
            fn () => RaceSession::factory()->create(['session_start' => Carbon::tomorrow()]),
            ['prediction' => 'not-an-array'],
            ['prediction' => 'The prediction field must be an array.']
        ];
        yield 'The length of the prediction array must be equal to 10' => [
            fn () => RaceSession::factory()->create(['session_start' => Carbon::tomorrow()]),
            ['prediction' => [1]],
            ['prediction' => 'The prediction field must contain 10 items.']
        ];
        yield 'The prediction array must contain integers' => [
            fn () => RaceSession::factory()->create(['session_start' => Carbon::tomorrow()]),
            ['prediction' => ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a']],
            [
                'prediction.0' => 'The prediction.0 field must be an integer.',
                'prediction.1' => 'The prediction.1 field must be an integer.',
                'prediction.2' => 'The prediction.2 field must be an integer.',
                'prediction.3' => 'The prediction.3 field must be an integer.',
                'prediction.4' => 'The prediction.4 field must be an integer.',
                'prediction.5' => 'The prediction.5 field must be an integer.',
                'prediction.6' => 'The prediction.6 field must be an integer.',
                'prediction.7' => 'The prediction.7 field must be an integer.',
                'prediction.8' => 'The prediction.8 field must be an integer.',
                'prediction.9' => 'The prediction.9 field must be an integer.',
            ]
        ];
        yield 'The drivers must exist' => [
            fn () => RaceSession::factory()->create(['session_start' => Carbon::tomorrow()]),
            [
                'prediction' => [-1, -1, -1, -1, -1, -1, -1, -1, -1, -1]
            ],
            [
                'prediction' => 'Not all drivers exists or have active contracts.',
            ]
        ];
        yield 'The prediction array must contain driver IDs with an active contract on session start' => [
            fn () => RaceSession::factory()->create(['session_start' => Carbon::tomorrow()]),
            [
                'prediction' => fn () => Driver::factory()
                    ->has(DriverContract::factory()->state(['start_date' => Carbon::tomorrow()->addDays(10)]), 'contracts')
                    ->count(10)
                    ->create()
                    ->map(fn (Driver $d) => $d->id)
            ],
            [
                'prediction' => 'Not all drivers exists or have active contracts.',
            ],
        ];
    }

    public function test_making_a_prediction_for_a_non_guessable_session_results_in_a_not_found_error(): void
    {
        $user = User::factory()->create();
        $raceSession = RaceSession::factory()->create(['guessable' => false]);

        $route = route('prediction.store', ['raceSession' => $raceSession->id]);
        $response = $this->actingAs($user)->postJson($route);

        $response->assertNotFound();
    }
}
