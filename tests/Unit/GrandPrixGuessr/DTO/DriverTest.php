<?php

declare(strict_types=1);

namespace Tests\Unit\GrandPrixGuessr\DTO;

use App\GrandPrixGuessr\DTO\Driver;
use App\GrandPrixGuessr\DTO\Team;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DriverTest extends TestCase
{
    public function test_a_driver_can_be_created(): void
    {
        $driver = new Driver(1, 'Max Verstappen', new Team(1, 'Red Bull Racing'));

        $this->assertSame(1, $driver->id);
        $this->assertSame('Max Verstappen', $driver->name);
        $this->assertSame(1, $driver->team->id);
        $this->assertSame('Red Bull Racing', $driver->team->name);
    }

    public function test_a_driver_id_cannot_be_below_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver id can not be less than 0');

        new Driver(-1, 'Max Verstappen', new Team(1, 'Red Bull Racing'));
    }

    public function test_a_driver_name_cannot_be_blank(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver name can not be empty');

        new Driver(0, '', new Team(1, 'Red Bull Racing'));
    }
}
