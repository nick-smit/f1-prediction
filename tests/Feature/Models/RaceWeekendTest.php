<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\RaceWeekend;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RaceWeekendTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_race_weekend_can_be_persisted(): void
    {
        /** @var RaceWeekend $driver */
        $driver = RaceWeekend::factory()->make();

        $success = $driver->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(RaceWeekend::class, 1);
    }
}
