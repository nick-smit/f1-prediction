<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\RaceSession;

use App\Actions\RaceSession\CalculateScores;
use App\Actions\RaceSession\ImportSessionResults;
use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultScraper;
use App\GrandPrixGuessr\Session\SessionType;
use App\Models\Driver;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use App\Models\SessionResult;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

final class ImportSessionResultsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_result_will_be_saved_if_every_driver_exists(): void
    {
        $this->instance(
            SessionResultScraper::class,
            Mockery::mock(SessionResultScraper::class, function (MockInterface $mock): void {
                $mock->shouldReceive('scrape')
                    ->with(2024, 'bahrein', SessionType::Race)
                    ->once()
                    ->andReturn(new Collection([
                        'Max Verstappen',
                        'Sergio Perez',
                        'Lando Norris',
                        'Oscar Piastri',
                        'Carlos Sainz',
                        'Charles Leclerc',
                        'Lewis Hamilton',
                        'George Russell',
                        'Fernando Alonso',
                        'Lance Stroll',
                    ]));
            })
        );

        $raceWeekend = RaceWeekend::factory()
            ->has(RaceSession::factory()->state([
                'session_start' => new DateTime('2024-01-01'),
                'type' => SessionType::Race,
            ]))
            ->create([
                'stats_f1_name' => 'bahrein'
            ]);

        Driver::factory()
            ->count(10)
            ->sequence(
                ['name' => 'Lance Stroll'],
                ['name' => 'Sergio Perez'],
                ['name' => 'Fernando Alonso'],
                ['name' => 'Oscar Piastri'],
                ['name' => 'Lewis Hamilton'],
                ['name' => 'Charles Leclerc'],
                ['name' => 'Lando Norris'],
                ['name' => 'Carlos Sainz'],
                ['name' => 'George Russell'],
                ['name' => 'Max Verstappen'],
            )
            ->create();

        $raceSession = $raceWeekend->raceSessions->first();
        $this->queryCounted(fn () => $this->app->make(ImportSessionResults::class)->handle($raceSession));

        $this->assertQueryCount(5);
        $this->assertDatabaseCount(SessionResult::class, 1);
        $this->assertNotNull($raceSession->sessionResult);
        $this->assertSame('Max Verstappen', $raceSession->sessionResult->p1->name);
        $this->assertSame('Sergio Perez', $raceSession->sessionResult->p2->name);
        $this->assertSame('Lando Norris', $raceSession->sessionResult->p3->name);
        $this->assertSame('Oscar Piastri', $raceSession->sessionResult->p4->name);
        $this->assertSame('Carlos Sainz', $raceSession->sessionResult->p5->name);
        $this->assertSame('Charles Leclerc', $raceSession->sessionResult->p6->name);
        $this->assertSame('Lewis Hamilton', $raceSession->sessionResult->p7->name);
        $this->assertSame('George Russell', $raceSession->sessionResult->p8->name);
        $this->assertSame('Fernando Alonso', $raceSession->sessionResult->p9->name);
        $this->assertSame('Lance Stroll', $raceSession->sessionResult->p10->name);
    }

    public function test_the_job_crashes_if_it_misses_a_driver(): void
    {
        $this->instance(
            SessionResultScraper::class,
            Mockery::mock(SessionResultScraper::class, function (MockInterface $mock): void {
                $mock->shouldReceive('scrape')
                    ->with(2024, 'bahrein', SessionType::Race)
                    ->once()
                    ->andReturn(new Collection([
                        'Max Verstappen',
                    ]));
            })
        );

        $raceWeekend = RaceWeekend::factory()
            ->has(RaceSession::factory()->state([
                'session_start' => new DateTime('2024-01-01'),
                'type' => SessionType::Race,
            ]))
            ->create([
                'stats_f1_name' => 'bahrein'
            ]);

        $raceSession = $raceWeekend->raceSessions->first();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Driver with name Max Verstappen could not be found.');
        $this->app->make(ImportSessionResults::class)->handle($raceSession);
    }

    public function test_it_calculates_scores(): void
    {
        $this->instance(
            SessionResultScraper::class,
            Mockery::mock(SessionResultScraper::class, function (MockInterface $mock): void {
                $mock->shouldReceive('scrape')
                    ->with(2024, 'bahrein', SessionType::Race)
                    ->once()
                    ->andReturn((new Collection())->pad(10, 'Max Verstappen'));
            })
        );

        Driver::factory()->create(['name' => 'Max Verstappen']);

        $raceWeekend = RaceWeekend::factory()
            ->has(RaceSession::factory()->state([
                'session_start' => new DateTime('2024-01-01'),
                'type' => SessionType::Race,
            ]))
            ->create([
                'stats_f1_name' => 'bahrein'
            ]);

        $raceSession = $raceWeekend->raceSessions->first();
        $this->mock(CalculateScores::class)
            ->expects('handle')
            ->once()
            ->withArgs(fn (RaceSession $session): bool => $session->id === $raceSession->id);

        $this->app->make(ImportSessionResults::class)->handle($raceSession);
    }
}
