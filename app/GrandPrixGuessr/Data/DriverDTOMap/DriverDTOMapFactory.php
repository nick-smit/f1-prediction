<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\Data\DriverDTOMap;

use App\GrandPrixGuessr\DTO\Driver as DriverDTO;
use App\GrandPrixGuessr\DTO\Team as TeamDTO;
use App\Models\DriverContract;
use DateTimeInterface;

class DriverDTOMapFactory
{
    public function create(DateTimeInterface $dateTime = null): DriverDTOMap
    {
        $activeContracts = DriverContract::active($dateTime)->with(['driver', 'team'])->get();

        $drivers = [];
        foreach ($activeContracts as $activeContract) {
            if (!isset($teams[$activeContract->team_id])) {
                $teams[$activeContract->team_id] = new TeamDTO($activeContract->team->id, $activeContract->team->name);
            }

            $drivers[$activeContract->driver_id] = new DriverDTO(
                $activeContract->driver_id,
                $activeContract->driver->name,
                new TeamDTO($activeContract->team->id, $activeContract->team->name)
            );
        }

        return new DriverDTOMap($drivers);
    }
}
