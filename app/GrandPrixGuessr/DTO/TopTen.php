<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\DTO;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class TopTen
{
    /**
     * @param  Driver[]  $drivers
     *
     * @throws AssertionFailedException
     */
    private function __construct(
        public array $drivers,
    ) {
        Assertion::allIsInstanceOf($drivers, Driver::class, 'Drivers must be type of '.Driver::class);
        Assertion::count($drivers, 10, 'The amount of drivers in a top 10 must be equal to ten.');

        $uniqueDrivers = [];
        $uniqueTeams = [];
        foreach ($this->drivers as $driver) {
            Assertion::notInArray($driver->id, $uniqueDrivers, 'A top 10 cannot have the same driver more than once');
            $uniqueDrivers[] = $driver->id;

            $uniqueTeams[$driver->team->id] ??= 0;
            ++$uniqueTeams[$driver->team->id];
            Assertion::max($uniqueTeams[$driver->team->id], 2, 'A top 10 cannot contain a team more than twice');
        }
    }

    /**
     * @param  Driver[]  $drivers
     *
     * @throws AssertionFailedException
     */
    public static function fromArray(array $drivers): TopTen
    {
        return new self($drivers);
    }
}
