<?php

declare(strict_types=1);

namespace Tests\Unit\GrandPrixGuessr\DTO;

use App\GrandPrixGuessr\DTO\Driver;
use App\GrandPrixGuessr\DTO\Team;
use App\GrandPrixGuessr\DTO\TopTen;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class TopTenTest extends TestCase
{
    public function test_a_top_ten_can_be_created(): void
    {
        $redBullRacing = new Team(1, 'Red Bull Racing');
        $mcLaren = new Team(3, 'McLaren');
        $ferrari = new Team(6, 'Ferrari');
        $mercedes = new Team(10, 'Mercedes');
        $astonMartin = new Team(5, 'Aston Martin');

        $topTen = TopTen::fromArray([
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(2, 'Sergio Perez', $redBullRacing),
            new Driver(3, 'Charles Leclerc', $ferrari),
            new Driver(4, 'Carlos Sainz', $ferrari),
            new Driver(5, 'Lando Norris', $mcLaren),
            new Driver(6, 'Oscar Piastri', $mcLaren),
            new Driver(7, 'Lewis Hamilton', $mercedes),
            new Driver(8, 'George Russell', $mercedes),
            new Driver(9, 'Fernando Alonso', $astonMartin),
            new Driver(10, 'Lance Stroll', $astonMartin),
        ]);

        $this->assertCount(10, $topTen);
        $this->assertEquals(new Driver(1, 'Max Verstappen', $redBullRacing), $topTen->drivers[0]);
        $this->assertEquals(new Driver(10, 'Lance Stroll', $astonMartin), $topTen->drivers[9]);
    }

    public function test_a_top_ten_cannot_have_less_than_ten_drivers(): void
    {
        $redBullRacing = new Team(1, 'Red Bull Racing');
        $mcLaren = new Team(3, 'McLaren');
        $ferrari = new Team(6, 'Ferrari');
        $mercedes = new Team(10, 'Mercedes');
        $astonMartin = new Team(5, 'Aston Martin');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The amount of drivers in a top 10 must be equal to 10.');

        TopTen::fromArray([
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(2, 'Sergio Perez', $redBullRacing),
            new Driver(5, 'Lando Norris', $mcLaren),
            new Driver(6, 'Oscar Piastri', $mcLaren),
            new Driver(3, 'Charles Leclerc', $ferrari),
            new Driver(4, 'Carlos Sainz', $ferrari),
            new Driver(7, 'Lewis Hamilton', $mercedes),
            new Driver(8, 'George Russell', $mercedes),
            new Driver(9, 'Fernando Alonso', $astonMartin),
        ]);
    }

    public function test_a_top_ten_cannot_have_more_than_ten_drivers(): void
    {
        $redBullRacing = new Team(1, 'Red Bull Racing');
        $mcLaren = new Team(3, 'McLaren');
        $ferrari = new Team(6, 'Ferrari');
        $mercedes = new Team(10, 'Mercedes');
        $astonMartin = new Team(5, 'Aston Martin');
        $williams = new Team(11, 'Williams');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The amount of drivers in a top 10 must be equal to 10.');

        TopTen::fromArray([
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(2, 'Sergio Perez', $redBullRacing),
            new Driver(5, 'Lando Norris', $mcLaren),
            new Driver(6, 'Oscar Piastri', $mcLaren),
            new Driver(3, 'Charles Leclerc', $ferrari),
            new Driver(4, 'Carlos Sainz', $ferrari),
            new Driver(7, 'Lewis Hamilton', $mercedes),
            new Driver(8, 'George Russell', $mercedes),
            new Driver(9, 'Fernando Alonso', $astonMartin),
            new Driver(10, 'Fernando Alonso', $astonMartin),
            new Driver(11, 'Logan Sargeant', $williams),
        ]);
    }

    public function test_a_top_ten_cannot_have_the_same_driver_twice(): void
    {
        $redBullRacing = new Team(1, 'Red Bull Racing');
        $mcLaren = new Team(3, 'McLaren');
        $ferrari = new Team(6, 'Ferrari');
        $mercedes = new Team(10, 'Mercedes');
        $astonMartin = new Team(5, 'Aston Martin');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A top X cannot have the same driver more than once');

        TopTen::fromArray([
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(5, 'Lando Norris', $mcLaren),
            new Driver(6, 'Oscar Piastri', $mcLaren),
            new Driver(3, 'Charles Leclerc', $ferrari),
            new Driver(4, 'Carlos Sainz', $ferrari),
            new Driver(7, 'Lewis Hamilton', $mercedes),
            new Driver(8, 'George Russell', $mercedes),
            new Driver(9, 'Fernando Alonso', $astonMartin),
            new Driver(10, 'Lance Stroll', $astonMartin),
        ]);
    }

    public function test_a_top_ten_cannot_have_the_same_team_three_times(): void
    {
        $redBullRacing = new Team(1, 'Red Bull Racing');
        $mcLaren = new Team(3, 'McLaren');
        $ferrari = new Team(6, 'Ferrari');
        $mercedes = new Team(10, 'Mercedes');
        $astonMartin = new Team(5, 'Aston Martin');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A top X cannot contain a team more than twice');

        TopTen::fromArray([
            new Driver(0, 'Mark Webber', $redBullRacing),
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(2, 'Sergio Perez', $redBullRacing),
            new Driver(3, 'Charles Leclerc', $ferrari),
            new Driver(4, 'Carlos Sainz', $ferrari),
            new Driver(5, 'Lando Norris', $mcLaren),
            new Driver(6, 'Oscar Piastri', $mcLaren),
            new Driver(7, 'Lewis Hamilton', $mercedes),
            new Driver(8, 'George Russell', $mercedes),
            new Driver(9, 'Fernando Alonso', $astonMartin),
        ]);
    }

    public function test_a_top_ten_must_consist_of_drivers(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Drivers must be type of App\GrandPrixGuessr\DTO\Driver');

        TopTen::fromArray([
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
            new stdClass(),
        ]);
    }
}
