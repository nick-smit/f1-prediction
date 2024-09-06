<?php

declare(strict_types=1);

namespace Tests\Unit\GrandPrixGuessr\Calculation;

use App\GrandPrixGuessr\Calculation\ScoreCalculation;
use App\GrandPrixGuessr\DTO\Driver;
use App\GrandPrixGuessr\DTO\Team;
use App\GrandPrixGuessr\DTO\TopTen;
use App\GrandPrixGuessr\DTO\TopThree;
use DeepCopy\DeepCopy;
use PHPUnit\Framework\TestCase;

final class ScoreCalculationTest extends TestCase
{
    private ScoreCalculation $scoreCalculation;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->scoreCalculation = new ScoreCalculation();
    }

    public function test_everything_wrong_scores_zero(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopThree::fromArray([
            $driverMap['VER'],
            $driverMap['PER'],
            $driverMap['NOR'],
        ]);

        $guess = TopThree::fromArray([
            $driverMap['RIC'],
            $driverMap['TSU'],
            $driverMap['GAS'],
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(0, $score);
    }

    public function test_everything_okay_scores_fifteen(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopThree::fromArray([
            $driverMap['VER'],
            $driverMap['PER'],
            $driverMap['NOR'],
        ]);

        // Use deep copy to make sure every instance of Driver or Team has a different instance ID
        $copier = new DeepCopy();
        $guess = TopThree::fromArray([
            $copier->copy($driverMap['VER']),
            $copier->copy($driverMap['PER']),
            $copier->copy($driverMap['NOR']),
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(15, $score);
    }

    public function test_one_okay_scores_five(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopThree::fromArray([
            $driverMap['VER'],
            $driverMap['PER'],
            $driverMap['NOR'],
        ]);

        // Use deep copy to make sure every instance of Driver or Team has a different instance ID
        $copier = new DeepCopy();
        $guess = TopThree::fromArray([
            $copier->copy($driverMap['VER']),
            $copier->copy($driverMap['LEC']),
            $copier->copy($driverMap['SAI']),
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(5, $score);
    }

    public function test_in_top_but_one_place_too_low_scores_three(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopThree::fromArray([
            $driverMap['VER'],
            $driverMap['PER'],
            $driverMap['NOR'],
        ]);

        // Use deep copy to make sure every instance of Driver or Team has a different instance ID
        $copier = new DeepCopy();
        $guess = TopThree::fromArray([
            $copier->copy($driverMap['LEC']),
            $copier->copy($driverMap['VER']),
            $copier->copy($driverMap['SAI']),
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(3, $score);
    }

    public function test_in_top_but_one_place_too_high_scores_three(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopThree::fromArray([
            $driverMap['PER'],
            $driverMap['NOR'],
            $driverMap['VER'],
        ]);

        // Use deep copy to make sure every instance of Driver or Team has a different instance ID
        $copier = new DeepCopy();
        $guess = TopThree::fromArray([
            $copier->copy($driverMap['LEC']),
            $copier->copy($driverMap['VER']),
            $copier->copy($driverMap['SAI']),
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(3, $score);
    }

    public function test_teammate_swap_scores_two(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopThree::fromArray([
            $driverMap['PER'],
            $driverMap['NOR'],
            $driverMap['ALB'],
        ]);

        // Use deep copy to make sure every instance of Driver or Team has a different instance ID
        $copier = new DeepCopy();
        $guess = TopThree::fromArray([
            $copier->copy($driverMap['VER']),
            $copier->copy($driverMap['LEC']),
            $copier->copy($driverMap['SAI']),
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(2, $score);
    }

    public function test_in_top_but_more_than_one_place_off(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopThree::fromArray([
            $driverMap['PER'],
            $driverMap['NOR'],
            $driverMap['ALB'],
        ]);

        // Use deep copy to make sure every instance of Driver or Team has a different instance ID
        $copier = new DeepCopy();
        $guess = TopThree::fromArray([
            $copier->copy($driverMap['VER']),
            $copier->copy($driverMap['LEC']),
            $copier->copy($driverMap['SAI']),
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(2, $score);
    }

    public function test_real_world_example(): void
    {
        $driverMap = $this->getDriverMap();
        $raceResult = TopTen::fromArray([
            $driverMap['VER'],
            $driverMap['NOR'],
            $driverMap['PIA'],
            $driverMap['LEC'],
            $driverMap['HAM'],
            $driverMap['RUS'],
            $driverMap['SAI'],
            $driverMap['PER'],
            $driverMap['HUL'],
            $driverMap['ALB'],
        ]);

        // Use deep copy to make sure every instance of Driver or Team has a different instance ID
        $copier = new DeepCopy();
        $guess = TopTen::fromArray([
            $copier->copy($driverMap['VER']), // 5
            $copier->copy($driverMap['PIA']), // 3
            $copier->copy($driverMap['NOR']), // 3
            $copier->copy($driverMap['SAI']), // 2
            $copier->copy($driverMap['PER']), // 1
            $copier->copy($driverMap['RUS']), // 5
            $copier->copy($driverMap['ALB']), // 1
            $copier->copy($driverMap['BOT']), // 0
            $copier->copy($driverMap['HAM']), // 1
            $copier->copy($driverMap['ALO']), // 0
        ]);

        $score = $this->scoreCalculation->calculate($raceResult, $guess);

        $this->assertSame(21, $score);
    }

    private function getDriverMap(): array
    {
        $redBullRacing = new Team(0, 'Red Bull Racing');
        $rB = new Team(1, 'RB');
        $mcLaren = new Team(2, 'McLaren');
        $alpine = new Team(3, 'Alpine');
        $astonMartin = new Team(4, 'Aston Martin');
        $ferrari = new Team(5, 'Ferrari');
        $haasF1Team = new Team(6, 'Haas F1 Team');
        $williams = new Team(7, 'Williams');
        $kickSauber = new Team(8, 'Kick Sauber');
        $mercedes = new Team(9, 'Mercedes');


        return [
            'VER' => new Driver(1, 'Max VERSTAPPEN', $redBullRacing),
            'PER' => new Driver(11, 'Sergio PEREZ', $redBullRacing),
            'NOR' => new Driver(4, 'Lando NORRIS', $mcLaren),
            'PIA' => new Driver(81, 'Oscar PIASTRI', $mcLaren),
            'ALO' => new Driver(14, 'Fernando ALONSO', $astonMartin),
            'STR' => new Driver(18, 'Lance STROLL', $astonMartin),
            'LEC' => new Driver(16, 'Charles LECLERC', $ferrari),
            'SAI' => new Driver(55, 'Carlos SAINZ', $ferrari),
            'MAG' => new Driver(20, 'Kevin MAGNUSSEN', $haasF1Team),
            'HUL' => new Driver(27, 'Nico HULKENBERG', $haasF1Team),
            'RIC' => new Driver(3, 'Daniel RICCIARDO', $rB),
            'TSU' => new Driver(22, 'Yuki TSUNODA', $rB),
            'GAS' => new Driver(10, 'Pierre GASLY', $alpine),
            'OCO' => new Driver(31, 'Esteban OCON', $alpine),
            'ALB' => new Driver(23, 'Alexander ALBON', $williams),
            'COL' => new Driver(43, 'Franco COLAPINTO', $williams),
            'HAM' => new Driver(44, 'Lewis HAMILTON', $mercedes),
            'RUS' => new Driver(63, 'George RUSSELL', $mercedes),
            'ZHO' => new Driver(24, 'ZHOU Guanyu', $kickSauber),
            'BOT' => new Driver(77, 'Valtteri BOTTAS', $kickSauber),
        ];
    }
}
