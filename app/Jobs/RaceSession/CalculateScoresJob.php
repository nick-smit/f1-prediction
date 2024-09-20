<?php

declare(strict_types=1);

namespace App\Jobs\RaceSession;

use App\GrandPrixGuessr\Calculation\ScoreCalculation;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMap;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMapFactory;
use App\GrandPrixGuessr\DTO\TopTen;
use App\Models\Guess;
use App\Models\RaceSession;
use App\Models\SessionResult;
use Assert\Assertion;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateScoresJob
{
    use Dispatchable;

    public function __construct(private readonly RaceSession $raceSession)
    {
    }

    public function handle(ScoreCalculation $scoreCalculation, DriverDTOMapFactory $driverDTOMapFactory): void
    {
        if ($this->raceSession->guesses()->count() === 0) {
            // Nothing to do.
            return;
        }

        $driverDTOMap = $driverDTOMapFactory->create($this->raceSession->session_start);
        Assertion::false($driverDTOMap->isEmpty(), 'The driver DTO map should not be empty.');

        $sessionResultDTO = $this->getTopTen($driverDTOMap, $this->raceSession->sessionResult);

        /** @var Guess $guess */
        foreach ($this->raceSession->guesses()->cursor() as $guess) {
            $guessDTO = $this->getTopTen($driverDTOMap, $guess);

            $score = $scoreCalculation->calculate($sessionResultDTO, $guessDTO);

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
