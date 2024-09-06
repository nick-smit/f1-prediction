<?php

declare(strict_types=1);

namespace Tests\Unit\GrandPrixGuessr\DTO;

use App\GrandPrixGuessr\DTO\Driver;
use App\GrandPrixGuessr\DTO\Team;
use App\GrandPrixGuessr\DTO\TopThree;
use PHPUnit\Framework\TestCase;

final class TopThreeTest extends TestCase
{
    public function test_a_top_three_can_be_created(): void
    {
        $redBullRacing = new Team(1, 'Red Bull Racing');
        $ferrari = new Team(6, 'Ferrari');

        $topThree = TopThree::fromArray([
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(2, 'Sergio Perez', $redBullRacing),
            new Driver(3, 'Charles Leclerc', $ferrari),
        ]);

        $this->assertCount(3, $topThree);
        $this->assertEquals(new Driver(1, 'Max Verstappen', $redBullRacing), $topThree->drivers[0]);
        $this->assertEquals(new Driver(3, 'Charles Leclerc', $ferrari), $topThree->drivers[2]);
    }

    public function test_a_top_three_is_iterable(): void
    {
        $redBullRacing = new Team(1, 'Red Bull Racing');
        $ferrari = new Team(6, 'Ferrari');

        $drivers = [
            new Driver(1, 'Max Verstappen', $redBullRacing),
            new Driver(2, 'Sergio Perez', $redBullRacing),
            new Driver(3, 'Charles Leclerc', $ferrari),
        ];

        $topThree = TopThree::fromArray($drivers);

        $this->assertCount(3, $topThree);
        $this->assertEquals(new Driver(1, 'Max Verstappen', $redBullRacing), $topThree->drivers[0]);
        $this->assertEquals(new Driver(3, 'Charles Leclerc', $ferrari), $topThree->drivers[2]);

        foreach ($topThree as $key => $driver) {
            $this->assertSame($drivers[$key], $driver);
        }
    }
}
