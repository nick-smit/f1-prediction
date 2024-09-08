<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\DriverContract;
use App\Models\Team;
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
}
