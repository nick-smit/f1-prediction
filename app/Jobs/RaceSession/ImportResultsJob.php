<?php

declare(strict_types=1);

namespace App\Jobs\RaceSession;

use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultScraper;
use App\Models\Driver;
use App\Models\RaceSession;
use App\Models\SessionResult;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use RuntimeException;

class ImportResultsJob
{
    use Dispatchable;

    public function __construct(private readonly RaceSession $raceSession)
    {
    }

    public function handle(SessionResultScraper $scraper): void
    {
        $results = $scraper->scrape(
            $this->raceSession->raceWeekend->start_date->year,
            $this->raceSession->raceWeekend->stats_f1_name,
            $this->raceSession->type
        );

        $drivers = Driver::query()
            ->whereIn('name', $results)
            ->pluck('id', 'name');

        /** @var SessionResult $sessionResult */
        $sessionResult = $this->raceSession->sessionResult()->firstOrNew();
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
    }

    private function getDriver(Collection $drivers, string $name)
    {
        if (!$drivers->has($name)) {
            throw new RuntimeException('Driver with name ' . $name . ' could not be found.');
        }

        return $drivers->get($name);
    }
}
