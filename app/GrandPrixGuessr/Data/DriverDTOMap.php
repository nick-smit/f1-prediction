<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\Data;

use App\GrandPrixGuessr\DTO\Driver as DriverDTO;
use App\GrandPrixGuessr\DTO\Team as TeamDTO;
use App\Models\DriverContract;
use Assert\Assertion;
use Assert\AssertionFailedException;

class DriverDTOMap
{
    public function __construct(private array $drivers)
    {
        Assertion::allIsInstanceOf($drivers, DriverDTO::class);
    }

    /**
     * @throws AssertionFailedException
     */
    public function getDriver(int $id): DriverDTO
    {
        Assertion::keyExists($this->drivers, $id);

        return $this->drivers[$id];
    }

    public function isEmpty(): bool
    {
        return $this->drivers === [];
    }

    /**
     * @throws AssertionFailedException
     */
    public static function createDriverDTOMap(): self
    {
        $activeContracts = DriverContract::active()->with(['driver', 'team'])->get();

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

        return new self($drivers);
    }
}
