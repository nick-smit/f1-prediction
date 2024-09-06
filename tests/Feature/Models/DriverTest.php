<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DriverTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_driver_can_be_persisted(): void
    {
        /** @var Driver $driver */
        $driver = Driver::factory()->make();

        $success = $driver->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(Driver::class, 1);
    }
}
