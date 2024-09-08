<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DriverContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_driver_contract_can_be_persisted(): void
    {
        /** @var DriverContract $driverContract */
        $driverContract = DriverContract::factory()->make();

        $success = $driverContract->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(DriverContract::class, 1);
        $this->assertInstanceOf(Team::class, $driverContract->team);
        $this->assertInstanceOf(Driver::class, $driverContract->driver);
    }
}
