<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use App\Models\DriverContract;
use Carbon\Carbon;
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

    public function test_a_can_retrieve_the_current_contract_when_contracts_are_not_preloaded(): void
    {
        $driver = Driver::factory()->create();
        $contract = DriverContract::factory()->create([
            'driver_id' => $driver,
            'start_date' => Carbon::createFromDate(2021, 01, 01)->setTime(0, 0),
            'end_date' => null,
        ]);

        $currentContract = $driver->getCurrentContract();

        $this->assertEquals($currentContract->toArray(), $contract->toArray());
    }

    public function test_a_can_retrieve_the_current_contract_when_contracts_are_preloaded(): void
    {
        $driver = Driver::factory()->create();
        $contract = DriverContract::factory()->create([
            'driver_id' => $driver,
            'start_date' => Carbon::createFromDate(2021, 01, 01)->setTime(0, 0),
            'end_date' => null,
        ]);

        $driver->load('contracts');

        $currentContract = $this->queryCounted(fn () => $driver->getCurrentContract());

        $this->assertEquals($currentContract->toArray(), $contract->toArray());
        $this->assertQueryCount(0);
    }

    public function test_a_can_retrieve_the_current_contract_when_contracts_are_preloaded_2(): void
    {
        $driver = Driver::factory()->create();
        $contract = DriverContract::factory()->create([
            'driver_id' => $driver,
            'start_date' => Carbon::createFromDate(2021, 01, 01)->setTime(0, 0),
            'end_date' => Carbon::createFromDate(2022, 01, 01)->setTime(0, 0),
        ]);

        $driver->load('contracts');

        $currentContract = $this->queryCounted(fn () => $driver->getCurrentContract(Carbon::createFromDate(2021, 01, 01)));

        $this->assertEquals($currentContract->toArray(), $contract->toArray());
        $this->assertQueryCount(0);
    }

    public function test_a_can_retrieve_the_current_contract_when_contracts_are_preloaded_3(): void
    {
        $driver = Driver::factory()->create();
        DriverContract::factory()->create([
            'driver_id' => $driver,
            'start_date' => Carbon::createFromDate(2100, 01, 01)->setTime(0, 0),
            'end_date' => null,
        ]);

        $driver->load('contracts');

        $currentContract = $this->queryCounted(fn () => $driver->getCurrentContract(Carbon::createFromDate(2021, 01, 01)));

        $this->assertNull($currentContract);
        $this->assertQueryCount(0);
    }

    public function test_a_can_retrieve_the_current_contract_when_contracts_are_preloaded_4(): void
    {
        $driver = Driver::factory()->create();
        DriverContract::factory()->create([
            'driver_id' => $driver,
            'start_date' => Carbon::createFromDate(2100, 01, 01)->setTime(0, 0),
            'end_date' => Carbon::createFromDate(2100, 02, 01),
        ]);

        $driver->load('contracts');

        $currentContract = $this->queryCounted(fn () => $driver->getCurrentContract(Carbon::createFromDate(2021, 01, 01)));

        $this->assertNull($currentContract);
        $this->assertQueryCount(0);
    }

    public function test_a_can_retrieve_the_current_contract_when_contracts_are_preloaded_5(): void
    {
        $driver = Driver::factory()->create();
        DriverContract::factory()->create([
            'driver_id' => $driver,
            'start_date' => Carbon::createFromDate(1900, 01, 01)->setTime(0, 0),
            'end_date' => Carbon::createFromDate(1901, 02, 01),
        ]);

        $driver->load('contracts');

        $currentContract = $this->queryCounted(fn () => $driver->getCurrentContract(Carbon::createFromDate(2021, 01, 01)));

        $this->assertNull($currentContract);
        $this->assertQueryCount(0);
    }
}
