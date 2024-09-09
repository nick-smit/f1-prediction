<?php

declare(strict_types=1);

namespace Tests\Feature\GrandPrixGuessr\Service;

use App\GrandPrixGuessr\Calculation\ScoreCalculation;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMap;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMapFactory;
use App\GrandPrixGuessr\Service\ScoreCalculationService;
use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Guess;
use App\Models\RaceSession;
use App\Models\SessionResult;
use App\Models\Team;
use Assert\InvalidArgumentException;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

final class ScoreCalculationServiceTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_session_without_guesses_does_nothing(): void
    {
        $session = RaceSession::factory()->create();

        $result = $this->queryCounted(fn () => $this->app->make(ScoreCalculationService::class)
            ->handle($session));

        $this->assertQueryCount(1);
        $this->assertSame(0, $result);
    }

    public function test_an_assertion_is_thrown_when_the_driver_dto_map_is_empty(): void
    {
        $this->instance(
            DriverDTOMapFactory::class,
            Mockery::mock(DriverDTOMapFactory::class, function (MockInterface $mock): void {
                $mock->shouldReceive('create')
                    ->once()
                    ->andReturn(new DriverDTOMap([]));
            })
        );

        $session = RaceSession::factory()->create();
        Guess::factory()->count(10)->create(['race_session_id' => $session]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The driver DTO map should not be empty.');

        $this->app->make(ScoreCalculationService::class)
            ->handle($session);
    }

    public function test_scores_should_be_saved_for_a_guess(): void
    {
        // Create drivers, teams and driver contracts
        $teams = Team::factory()->count(5)->create();
        $drivers = Driver::factory()->count(10)->create();
        $sequence = [];
        /**
         * @var int $key
         * @var Driver $driver
         */
        foreach ($drivers as $key => $driver) {
            $sequence[] = ['driver_id' => $driver, 'team_id' => $teams[$key % 5]];
        }

        DriverContract::factory()->count(10)
            ->sequence(...$sequence)
            ->create(['start_date' => new DateTime('01-01-2024')]);
        // End create drivers, teams and driver contracts

        // Create session result and a guess
        $session = RaceSession::factory()->create([
            'session_start' => new DateTime('01-01-2024')
        ]);
        SessionResult::factory()->create([
            'race_session_id' => $session,
            'p1_id' => $drivers[0],
            'p2_id' => $drivers[1],
            'p3_id' => $drivers[2],
            'p4_id' => $drivers[3],
            'p5_id' => $drivers[4],
            'p6_id' => $drivers[5],
            'p7_id' => $drivers[6],
            'p8_id' => $drivers[7],
            'p9_id' => $drivers[8],
            'p10_id' => $drivers[9],
        ]);

        $guess = Guess::factory()->create([
            'race_session_id' => $session,
            'p1_id' => $drivers[0],
            'p2_id' => $drivers[1],
            'p3_id' => $drivers[2],
            'p4_id' => $drivers[3],
            'p5_id' => $drivers[4],
            'p6_id' => $drivers[5],
            'p7_id' => $drivers[6],
            'p8_id' => $drivers[7],
            'p9_id' => $drivers[8],
            'p10_id' => $drivers[9],
            'score' => null,
        ]);
        // End create session result and a guess

        $this->instance(
            ScoreCalculation::class,
            Mockery::mock(ScoreCalculation::class, function (MockInterface $mock): void {
                $mock->shouldReceive('calculate')
                    ->once()
                    ->andReturn(123);
            })
        );

        $result = $this->queryCounted(fn () => $this->app->make(ScoreCalculationService::class)
            ->handle($session));

        $this->assertSame(1, $result);
        $this->assertQueryCount(7);
        $this->assertSame(123, $guess->refresh()->score);
    }

    public function test_scores_should_be_saved_for_multiple_guesses(): void
    {
        // Create drivers, teams and driver contracts
        $teams = Team::factory()->count(5)->create();
        $drivers = Driver::factory()->count(10)->create();
        $sequence = [];
        /**
         * @var int $key
         * @var Driver $driver
         */
        foreach ($drivers as $key => $driver) {
            $sequence[] = ['driver_id' => $driver, 'team_id' => $teams[$key % 5]];
        }

        DriverContract::factory()->count(10)
            ->sequence(...$sequence)
            ->create(['start_date' => new DateTime('01-01-2024')]);
        // End create drivers, teams and driver contracts

        // Create session result and a guess
        $session = RaceSession::factory()->create([
            'session_start' => new DateTime('01-01-2024')
        ]);
        SessionResult::factory()->create([
            'race_session_id' => $session,
            'p1_id' => $drivers[0],
            'p2_id' => $drivers[1],
            'p3_id' => $drivers[2],
            'p4_id' => $drivers[3],
            'p5_id' => $drivers[4],
            'p6_id' => $drivers[5],
            'p7_id' => $drivers[6],
            'p8_id' => $drivers[7],
            'p9_id' => $drivers[8],
            'p10_id' => $drivers[9],
        ]);

        Guess::factory()->count(10)->create([
            'race_session_id' => $session,
            'p1_id' => $drivers[0],
            'p2_id' => $drivers[1],
            'p3_id' => $drivers[2],
            'p4_id' => $drivers[3],
            'p5_id' => $drivers[4],
            'p6_id' => $drivers[5],
            'p7_id' => $drivers[6],
            'p8_id' => $drivers[7],
            'p9_id' => $drivers[8],
            'p10_id' => $drivers[9],
            'score' => null,
        ]);
        // End create session result and a guess

        $this->instance(
            ScoreCalculation::class,
            Mockery::mock(ScoreCalculation::class, function (MockInterface $mock): void {
                $mock->shouldReceive('calculate')
                    ->andReturn(123);
            })
        );

        $result = $this->queryCounted(fn () => $this->app->make(ScoreCalculationService::class)
            ->handle($session));

        $this->assertSame(10, $result);
        $this->assertQueryCount(16);
    }

}
