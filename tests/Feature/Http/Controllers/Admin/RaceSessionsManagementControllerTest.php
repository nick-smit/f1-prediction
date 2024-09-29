<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Actions\RaceSession\CalculateScores;
use App\Actions\RaceSession\ImportSessionResults;
use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultNotFoundException;
use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultScraper;
use App\GrandPrixGuessr\Session\SessionType;
use App\Http\Controllers\Admin\RaceSessionsManagementController;
use App\Models\Guess;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use App\Models\SessionResult;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(RaceSessionsManagementController::class)]
final class RaceSessionsManagementControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_index_view(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        RaceSession::factory()->count(5)->create(
            [
                'session_end' => Carbon::tomorrow() // Otherwise it might trigger actions
            ]
        );

        $response = $this->queryCounted(fn () => $this->get(route('admin.race-sessions.index')));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): AssertableJson => $page
            ->component('Admin/RaceSessions/Index')
            ->has('race_sessions.data', 5, fn (AssertableInertia $page): AssertableJson => $page
                ->whereAllType([
                    'id' => 'integer',
                    'race_weekend_name' => 'string',
                    'type' => 'string',
                    'session_start' => 'string',
                    'session_end' => 'string',
                    'guesses' => 'integer',
                    'has_results' => 'boolean',
                ]))
            ->has('action_required', 0)
        );
        $this->assertQueryCount(4);
    }

    public function test_index_view_action_required(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $noSessionResult = RaceSession::factory()->create([
            'session_end' => Carbon::yesterday(),
        ]);
        $withGuesses = RaceSession::factory()
            ->has(SessionResult::factory())
            ->has(Guess::factory()->state(['score' => null]))
            ->create([
                'session_end' => Carbon::yesterday(),
            ]);

        $response = $this->queryCounted(fn () => $this->get(route('admin.race-sessions.index')));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): AssertableJson => $page
            ->component('Admin/RaceSessions/Index')
            ->has(
                'action_required.0',
                fn (AssertableInertia $page): AssertableJson => $page
                ->where('id', $noSessionResult->id)
                ->whereType('race_weekend_name', 'string')
                ->whereType('type', 'string')
                ->where('action', 'import-results')
            )->has(
                'action_required.1',
                fn (AssertableInertia $page): AssertableJson => $page
                ->where('id', $withGuesses->id)
                ->whereType('race_weekend_name', 'string')
                ->whereType('type', 'string')
                ->where('action', 'calculate-scores')
            )
        );
        $this->assertQueryCount(5);
    }

    public function test_show_view(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $session = RaceSession::factory()
            ->has(Guess::factory()->count(1))
            ->create(
                [
                'race_weekend_id' => RaceWeekend::factory()->create(['name' => 'Bahrain Grand Prix']),
                'type' => SessionType::Race,
                'session_start' => Carbon::today(),
                'session_end' => Carbon::tomorrow(), // Otherwise it might trigger actions
            ]
            );

        $response = $this->queryCounted(fn () => $this->get(route('admin.race-sessions.show', ['race_session' => $session->id])));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): AssertableJson => $page
            ->component('Admin/RaceSessions/Show')
            ->has('race_session', fn (AssertableInertia $page): AssertableJson => $page
                ->whereAll([
                    'id' => $session->id,
                    'race_weekend_name' => 'Bahrain Grand Prix',
                    'type' => 'race',
                    'session_start' => Carbon::today()->jsonSerialize(),
                    'session_end' => Carbon::tomorrow()->jsonSerialize(),
                    'guesses' => 1,
                    'has_results' => false,
                ]))
            ->where('action', null)
            ->where('results', null)
        );
        $this->assertQueryCount(4);
    }

    public function test_show_view_with_result(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $result = SessionResult::factory()
            ->has(RaceSession::factory()
                ->has(Guess::factory()->count(1))
                ->state(
                    [
                        'race_weekend_id' => RaceWeekend::factory()->create(['name' => 'Bahrain Grand Prix']),
                        'type' => SessionType::Race,
                        'session_start' => Carbon::yesterday(),
                        'session_end' => Carbon::yesterday(), // Otherwise it might trigger actions
                    ]
                ))
            ->create();

        $response = $this->queryCounted(fn () => $this->get(route('admin.race-sessions.show', ['race_session' => $result->race_session_id])));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page): AssertableJson => $page
            ->component('Admin/RaceSessions/Show')
            ->where('action', null)
            ->has('results')
        );
        $this->assertQueryCount(5);
    }

    public function test_imports_results_imports_results_and_calculates_scores(): void
    {

        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $session = RaceSession::factory()->create();
        $this->mock(ImportSessionResults::class)->expects('handle')
            ->once()
            ->withArgs(fn (RaceSession $expected) => $expected->is($session));

        $response = $this->post(route('admin.race-sessions.import-results', ['race_session' => $session->id]));

        $response->assertNoContent();

    }

    public function test_imports_results_fails_when_results_are_not_ready(): void
    {
        $this->instance(
            SessionResultScraper::class,
            Mockery::mock(SessionResultScraper::class, function (MockInterface $mock): void {
                $mock->shouldReceive('scrape')
                    ->once()
                    ->andThrow(new SessionResultNotFoundException('Session results were not found.'));
            })
        );

        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $session = RaceSession::factory()
            ->create();

        $response = $this->post(route('admin.race-sessions.import-results', ['race_session' => $session->id]));

        $response->assertNotFound();
        $response->assertJson(['message' => 'Session results were not found.']);
    }

    public function test_calculate_scores_calculates_scores(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $session = RaceSession::factory()->create();
        $this->mock(CalculateScores::class)->expects('handle')
            ->once()
            ->withArgs(fn (RaceSession $expected) => $expected->is($session));

        $response = $this->post(route('admin.race-sessions.calculate-scores', ['race_session' => $session->id]));

        $response->assertNoContent();
    }
}
