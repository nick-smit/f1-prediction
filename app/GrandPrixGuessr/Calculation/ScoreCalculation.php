<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\Calculation;

use App\GrandPrixGuessr\DTO\Driver;
use App\GrandPrixGuessr\DTO\TopX;

class ScoreCalculation
{
    private const int EXACT_MATCH_SCORE = 5;

    private const int ONE_PLACE_OFF_SCORE = 3;

    private const int TEAM_MATE_SWAPPED_SCORE = 2;

    private const int IN_TOP_BUT_MISSED_SCORE = 1;

    public function calculate(TopX $raceResult, TopX $guess): int
    {
        $resultDriverIds = array_map(static fn (Driver $driver): int => $driver->id, $raceResult->drivers);

        $score = 0;
        foreach ($raceResult as $position => $driver) {
            $guessedDriver = $guess->drivers[$position];
            if ($guessedDriver->id === $driver->id) {
                $score += self::EXACT_MATCH_SCORE;
                continue;
            }

            $previousDriver = $raceResult->drivers[$position - 1] ?? null;
            $nextDriver = $raceResult->drivers[$position + 1] ?? null;
            if ($guessedDriver->id === $previousDriver?->id || $guessedDriver->id === $nextDriver?->id) {
                // Subtract one as this will also count the IN_TOP_BUT_MISSED_SCORE score.
                $score += self::ONE_PLACE_OFF_SCORE;
            } elseif ($guessedDriver->team->id === $driver->team->id) {
                $score += self::TEAM_MATE_SWAPPED_SCORE;
            } elseif (in_array($guessedDriver->id, $resultDriverIds)) {
                $score += self::IN_TOP_BUT_MISSED_SCORE;
            }
        }

        return $score;
    }
}
