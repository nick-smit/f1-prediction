<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use Carbon\Carbon;
use DateTime;
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

    public function test_active_scope(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 2));

        DriverContract::factory()
            ->count(5)
            ->sequence(
                ['start_date' => new DateTime('01-01-2024'), 'end_date' => null], // Should be found
                ['start_date' => new DateTime('01-01-2024'), 'end_date' => new DateTime('31-12-2024')], // Should found
                ['start_date' => new DateTime('01-01-2025'), 'end_date' => null], // Should not be found
                ['start_date' => new DateTime('01-01-2025'), 'end_date' => new DateTime('31-12-2025')], // Should not be found
                ['start_date' => new DateTime('01-01-2023'), 'end_date' => new DateTime('31-12-2023')], // Should not be found
            )->create();

        $foundContracts = DriverContract::active()->get();

        $this->assertCount(2, $foundContracts);

        /** @var DriverContract $first */
        $first = $foundContracts->shift();
        $this->assertEquals(new DateTime('01-01-2024'), $first->start_date);
        $this->assertNull($first->end_date);

        /** @var DriverContract $second */
        $second = $foundContracts->shift();
        $this->assertEquals(new DateTime('01-01-2024'), $second->start_date);
        $this->assertEquals(new DateTime('31-12-2024'), $second->end_date);
    }
}
