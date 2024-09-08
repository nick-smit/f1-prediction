<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use App\Models\DriverContract;
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

    public function test_a_driver_can_have_a_contract(): void
    {
        $driver = Driver::factory()->create();
        $contract = DriverContract::factory()->make(['driver_id' => null]);

        $driver->contracts()->save($contract);

        $driver = $driver->refresh();

        $this->assertDatabaseCount(DriverContract::class, 1);
        $this->assertCount(1, $driver->contracts);
    }
}
