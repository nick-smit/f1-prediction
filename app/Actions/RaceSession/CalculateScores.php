<?php

declare(strict_types=1);

namespace App\Actions\RaceSession;

use App\GrandPrixGuessr\Calculation\ScoreCalculation;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMap;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMapFactory;
use App\GrandPrixGuessr\DTO\TopTen;
use App\Models\Guess;
use App\Models\RaceSession;
use App\Models\SessionResult;
use Assert\Assertion;

class CalculateScores
{
    public function __construct(private readonly ScoreCalculation $scoreCalculation, private readonly DriverDTOMapFactory $driverDTOMapFactory)
    {
    }

    /**
     * Execute the action.
     */
    public function handle(RaceSession $raceSession): void
    {
        if ($raceSession->guesses()->count() === 0) {
            // Nothing to do.
            return;
        }

        $driverDTOMap = $this->driverDTOMapFactory->create($raceSession->session_start);
        Assertion::false($driverDTOMap->isEmpty(), 'The driver DTO map should not be empty.');

        $sessionResultDTO = $this->getTopTen($driverDTOMap, $raceSession->sessionResult);

        /** @var Guess $guess */
        foreach ($raceSession->guesses()->cursor() as $guess) {
            $guessDTO = $this->getTopTen($driverDTOMap, $guess);

            $score = $this->scoreCalculation->calculate($sessionResultDTO, $guessDTO);

            $guess->score = $score;
            $guess->save();
        }
    }

    private function getTopTen(DriverDTOMap $driverDTOMap, SessionResult|Guess $sessionResultOrGuess): TopTen
    {
        return TopTen::fromArray([
            $driverDTOMap->getDriver($sessionResultOrGuess->p1_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p2_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p3_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p4_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p5_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p6_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p7_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p8_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p9_id),
            $driverDTOMap->getDriver($sessionResultOrGuess->p10_id),
        ]);
    }

}
