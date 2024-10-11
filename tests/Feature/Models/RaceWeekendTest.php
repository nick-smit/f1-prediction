<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\GrandPrixGuessr\Session\SessionType;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use Carbon\Carbon;
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

    public function test_creating_a_race_weekend_sets_its_slug(): void
    {
        $raceWeekend = RaceWeekend::factory()->make([
            'start_date' => Carbon::create(year: 2023),
            'name' => 'Bahrain Grand Prix',
        ]);

        $raceWeekend->save();

        $this->assertSame('2023-bahrain-grand-prix', $raceWeekend->fresh()->slug);
    }

    public function test_updating_a_race_weekend_sets_its_slug(): void
    {
        $raceWeekend = RaceWeekend::factory()->create([
            'start_date' => Carbon::create(year: 2023),
            'name' => 'Bahrain Grand Prix',
        ]);

        $raceWeekend->update(['start_date' => Carbon::create(year: 2025)]);

        $this->assertSame('2025-bahrain-grand-prix', $raceWeekend->fresh()->slug);
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

    public function test_a_race_weekend_retrieves_the_previous_event(): void
    {
        $expected = RaceWeekend::factory()
            ->create(['start_date' => Carbon::create(year: 2023)]);
        $current = RaceWeekend::factory()
            ->create(['start_date' => Carbon::create(year: 2024)]);

        $actual = $current->getPrevious();

        $this->assertModelIs($expected, $actual);
    }

    public function test_a_race_weekend_retrieves_the_next_event(): void
    {
        $current = RaceWeekend::factory()
            ->create(['start_date' => Carbon::create(year: 2023)]);
        $expected = RaceWeekend::factory()
            ->create(['start_date' => Carbon::create(year: 2024)]);

        $actual = $current->getNext();

        $this->assertModelIs($expected, $actual);
    }
}
