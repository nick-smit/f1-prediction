<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\DriverContract;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_team_can_be_persisted(): void
    {
        $team = Team::factory()->make();

        $success = $team->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(Team::class, 1);
    }

    public function test_a_team_can_have_contracts(): void
    {
        $team = Team::factory()->create();
        $contract = DriverContract::factory()->make(['team_id' => null]);

        $team->contracts()->save($contract);

        $team = $team->refresh();

        $this->assertDatabaseCount(DriverContract::class, 1);
        $this->assertCount(1, $team->contracts);
    }

    public function test_a_team_can_retrieve_the_current_contracts_when_contracts_are_not_preloaded(): void
    {
        $team = Team::factory()->create();
        $contract = DriverContract::factory()->create([
            'team_id' => $team,
            'start_date' => Carbon::createFromDate(2021, 01, 01)->setTime(0, 0),
            'end_date' => null,
        ]);

        $currentContracts = $team->getCurrentContracts();

        $this->assertTrue($currentContracts->contains($contract));
    }

    public function test_a_team_can_retrieve_the_current_contracts_when_contracts_are_preloaded(): void
    {
        $team = Team::factory()->create();
        $contract = DriverContract::factory()->create([
            'team_id' => $team,
            'start_date' => Carbon::createFromDate(2021, 01, 01)->setTime(0, 0),
            'end_date' => null,
        ]);

        $team->load('contracts');

        $currentContracts = $this->queryCounted(fn () => $team->getCurrentContracts());

        $this->assertTrue($currentContracts->contains($contract));
        $this->assertQueryCount(0);
    }

    public function test_a_team_can_retrieve_the_current_contracts_when_contracts_are_preloaded_2(): void
    {
        $team = Team::factory()->create();
        $contract = DriverContract::factory()->create([
            'team_id' => $team,
            'start_date' => Carbon::createFromDate(2021, 01, 01)->setTime(0, 0),
            'end_date' => Carbon::createFromDate(2022, 01, 01)->setTime(0, 0),
        ]);

        $team->load('contracts');

        $currentContracts = $this->queryCounted(fn () => $team->getCurrentContracts(Carbon::createFromDate(2021, 01, 01)));

        $this->assertTrue($currentContracts->contains($contract));
        $this->assertQueryCount(0);
    }

    public function test_a_team_can_retrieve_the_current_contracts_when_contracts_are_preloaded_3(): void
    {
        $team = Team::factory()->create();
        DriverContract::factory()->create([
            'team_id' => $team,
            'start_date' => Carbon::createFromDate(2100, 01, 01)->setTime(0, 0),
            'end_date' => null,
        ]);

        $team->load('contracts');

        $currentContracts = $this->queryCounted(fn () => $team->getCurrentContracts(Carbon::createFromDate(2021, 01, 01)));

        $this->assertEmpty($currentContracts);
        $this->assertQueryCount(0);
    }

    public function test_a_team_can_retrieve_the_current_contracts_when_contracts_are_preloaded_4(): void
    {
        $team = Team::factory()->create();
        DriverContract::factory()->create([
            'team_id' => $team,
            'start_date' => Carbon::createFromDate(2100, 01, 01)->setTime(0, 0),
            'end_date' => Carbon::createFromDate(2100, 02, 01),
        ]);

        $team->load('contracts');

        $currentContracts = $this->queryCounted(fn () => $team->getCurrentContracts(Carbon::createFromDate(2021, 01, 01)));

        $this->assertEmpty($currentContracts);
        $this->assertQueryCount(0);
    }

    public function test_a_team_can_retrieve_the_current_contracts_when_contracts_are_preloaded_5(): void
    {
        $team = Team::factory()->create();
        DriverContract::factory()->create([
            'team_id' => $team,
            'start_date' => Carbon::createFromDate(1900, 01, 01)->setTime(0, 0),
            'end_date' => Carbon::createFromDate(1901, 02, 01),
        ]);

        $team->load('contracts');

        $currentContracts = $this->queryCounted(fn () => $team->getCurrentContracts(Carbon::createFromDate(2021, 01, 01)));

        $this->assertEmpty($currentContracts);
        $this->assertQueryCount(0);
    }

    public function test_a_team_can_retrieve_the_current_contracts_when_contracts_are_preloaded_6(): void
    {
        $team = Team::factory()->create();
        [$contract1, $contract2] = DriverContract::factory()->count(2)->create([
            'team_id' => $team,
            'start_date' => Carbon::createFromDate(2021, 01, 01)->setTime(0, 0),
            'end_date' => null,
        ]);

        $team->load('contracts');

        $currentContracts = $this->queryCounted(fn () => $team->getCurrentContracts());

        $this->assertQueryCount(0);
        $this->assertCount(2, $currentContracts);
        $this->assertTrue($currentContracts->contains($contract1));
        $this->assertTrue($currentContracts->contains($contract2));
    }
}
