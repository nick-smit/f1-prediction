<?php

declare(strict_types=1);

namespace App\Actions\RaceSession;

use App\GrandPrixGuessr\Calculation\ScoreCalculation;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMap;
use App\GrandPrixGuessr\Data\DriverDTOMap\DriverDTOMapFactory;
use App\GrandPrixGuessr\DTO\TopTen;
use App\Models\Prediction;
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
        if ($raceSession->predictions()->count() === 0) {
            // Nothing to do.
            return;
        }

        $driverDTOMap = $this->driverDTOMapFactory->create($raceSession->session_start);
        Assertion::false($driverDTOMap->isEmpty(), 'The driver DTO map should not be empty.');

        $sessionResultDTO = $this->getTopTen($driverDTOMap, $raceSession->sessionResult);

        /** @var Prediction $prediction */
        foreach ($raceSession->predictions()->cursor() as $prediction) {
            $predictionDTO = $this->getTopTen($driverDTOMap, $prediction);

            $score = $this->scoreCalculation->calculate($sessionResultDTO, $predictionDTO);

            $prediction->score = $score;
            $prediction->save();
        }
    }

    private function getTopTen(DriverDTOMap $driverDTOMap, SessionResult|Prediction $sessionResultOrPrediction): TopTen
    {
        return TopTen::fromArray([
            $driverDTOMap->getDriver($sessionResultOrPrediction->p1_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p2_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p3_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p4_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p5_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p6_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p7_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p8_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p9_id),
            $driverDTOMap->getDriver($sessionResultOrPrediction->p10_id),
        ]);
    }

}
