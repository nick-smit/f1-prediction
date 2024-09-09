<?php

declare(strict_types=1);

namespace Tests\Feature\GrandPrixGuessr\Data;

use App\GrandPrixGuessr\Data\DriverDTOMap;
use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DriverDTOMapTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_driver_dto_map_can_be_created_by_the_named_constructor(): void
    {
        Carbon::setTestNow(Carbon::create(2021, 1, 1));

        $driver = Driver::factory()->create(['name' => 'John Doe']);
        $team = Team::factory()->create(['name' => 'John Racing']);
        DriverContract::factory()->create([
            'driver_id' => $driver,
            'team_id' => $team,
            'start_date' => new DateTime('2021-01-01'),
            'end_date' => null,
        ]);

        $map = DriverDTOMap::createDriverDTOMap();

        $driverDTO = $map->getDriver($driver->id);

        $this->assertSame($driver->id, $driverDTO->id);
        $this->assertSame($driver->name, $driverDTO->name);
        $this->assertSame($team->id, $driverDTO->team->id);
        $this->assertSame($team->name, $driverDTO->team->name);
    }

    public function test_a_driver_dto_map_can_be_created_by_the_named_constructor_and_skips_inactive_contracts(): void
    {
        Carbon::setTestNow(Carbon::create(2021, 1, 1));

        $driver = Driver::factory()->create(['name' => 'John Doe']);
        $team = Team::factory()->create(['name' => 'John Racing']);
        DriverContract::factory()->create([
            'driver_id' => $driver,
            'team_id' => $team,
            'start_date' => new DateTime('2021-01-02'),
            'end_date' => null,
        ]);

        $map = DriverDTOMap::createDriverDTOMap();

        $this->assertTrue($map->isEmpty());
    }
}
