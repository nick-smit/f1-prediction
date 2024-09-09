<?php

declare(strict_types=1);

namespace Tests\Feature\GrandPrixGuessr\Data\DriverDTOMap;

use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMapFactory;
use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Override;
use Tests\TestCase;

final class DriverDTOMapFactoryTest extends TestCase
{
    use RefreshDatabase;

    private DriverDTOMapFactory $driverDTOMapFactory;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->driverDTOMapFactory = $this->app->make(DriverDTOMapFactory::class);
    }

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

        $map = $this->driverDTOMapFactory->create();

        $driverDTO = $map->getDriver($driver->id);

        $this->assertSame($driver->id, $driverDTO->id);
        $this->assertSame($driver->name, $driverDTO->name);
        $this->assertSame($team->id, $driverDTO->team->id);
        $this->assertSame($team->name, $driverDTO->team->name);
    }

    public function test_a_driver_dto_map_can_be_created_and_skips_inactive_contracts(): void
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

        $map = $this->driverDTOMapFactory->create();

        $this->assertTrue($map->isEmpty());
    }

    public function test_a_driver_dto_map_can_be_created_with_a_specified_date(): void
    {
        Carbon::setTestNow(Carbon::create(2021, 1, 1));

        $driver = Driver::factory()->create(['name' => 'John Doe']);
        $team = Team::factory()->create(['name' => 'John Racing']);
        DriverContract::factory()->create([
            'driver_id' => $driver,
            'team_id' => $team,
            'start_date' => new DateTime('2100-01-01'),
            'end_date' => null,
        ]);

        $map = $this->driverDTOMapFactory->create(new DateTime('2101-01-01'));

        $driverDTO = $map->getDriver($driver->id);

        $this->assertSame($driver->id, $driverDTO->id);
        $this->assertSame($driver->name, $driverDTO->name);
        $this->assertSame($team->id, $driverDTO->team->id);
        $this->assertSame($team->name, $driverDTO->team->name);
    }
}
