<?php

declare(strict_types=1);

namespace App\Actions\RaceSession;

use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultScraper;
use App\Models\Driver;
use App\Models\RaceSession;
use App\Models\SessionResult;
use Illuminate\Support\Collection;
use RuntimeException;

class ImportSessionResults
{
    public function __construct(private readonly SessionResultScraper $scraper, private readonly CalculateScores $calculateScores)
    {
    }

    /**
     * Execute the action.
     */
    public function handle(RaceSession $raceSession): void
    {
        $results = $this->scraper->scrape(
            $raceSession->raceWeekend->start_date->year,
            $raceSession->raceWeekend->stats_f1_name,
            $raceSession->type
        );

        $drivers = Driver::query()
            ->whereIn('name', $results)
            ->pluck('id', 'name');

        /** @var SessionResult $sessionResult */
        $sessionResult = $raceSession->sessionResult()->firstOrNew();
        $sessionResult->p1_id = $this->getDriver($drivers, $results[0]);
        $sessionResult->p2_id = $this->getDriver($drivers, $results[1]);
        $sessionResult->p3_id = $this->getDriver($drivers, $results[2]);
        $sessionResult->p4_id = $this->getDriver($drivers, $results[3]);
        $sessionResult->p5_id = $this->getDriver($drivers, $results[4]);
        $sessionResult->p6_id = $this->getDriver($drivers, $results[5]);
        $sessionResult->p7_id = $this->getDriver($drivers, $results[6]);
        $sessionResult->p8_id = $this->getDriver($drivers, $results[7]);
        $sessionResult->p9_id = $this->getDriver($drivers, $results[8]);
        $sessionResult->p10_id = $this->getDriver($drivers, $results[9]);

        $sessionResult->save();

        $this->calculateScores->handle($raceSession);
    }

    private function getDriver(Collection $drivers, string $name)
    {
        if (!$drivers->has($name)) {
            throw new RuntimeException('Driver with name ' . $name . ' could not be found.');
        }

        return $drivers->get($name);
    }
}
