<?php

declare(strict_types=1);

namespace Tests\Unit\GrandPrixGuessr\Data\DriverDTOMap;

use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMap;
use App\GrandPrixGuessr\DTO\Driver;
use App\GrandPrixGuessr\DTO\Team;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DriverDTOMapTest extends TestCase
{
    public function test_a_driver_dto_map_can_be_created(): void
    {
        $drivers = [
            1 => new Driver(
                1,
                'John Doe',
                new Team(
                    1,
                    'John Racing'
                )
            ),
            2 => new Driver(
                2,
                'Jane Doe',
                new Team(
                    2,
                    'Jane Racing'
                )
            ),
        ];

        $map = new DriverDTOMap($drivers);

        $driver1 = $map->getDriver(1);
        $this->assertSame(1, $driver1->id);
        $this->assertSame('John Doe', $driver1->name);
        $this->assertSame(1, $driver1->team->id);
        $this->assertSame('John Racing', $driver1->team->name);

        $driver2 = $map->getDriver(2);
        $this->assertSame(2, $driver2->id);
        $this->assertSame('Jane Doe', $driver2->name);
        $this->assertSame(2, $driver2->team->id);
        $this->assertSame('Jane Racing', $driver2->team->name);
    }

    public function test_a_driver_dto_map_must_only_contain_drivers(): void
    {
        $drivers = [
            2 => [
                'id' => 2,
                'name' => 'Jane Doe',
                'team' => [
                    'id' => 3,
                    'name' => 'Jane racing'
                ]
            ],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class "<ARRAY>" was expected to be instanceof of "App\GrandPrixGuessr\DTO\Driver" but is not.');

        new DriverDTOMap($drivers);
    }

    public function test_a_driver_dto_map_throws_an_exception_when_the_driver_is_not_found(): void
    {
        $drivers = [
            1 => new Driver(
                1,
                'John Doe',
                new Team(
                    1,
                    'John Racing'
                )
            ),
        ];

        $map = new DriverDTOMap($drivers);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Array does not contain an element with key "2"');
        $map->getDriver(2);
    }
}
