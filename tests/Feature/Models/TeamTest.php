<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

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
}
