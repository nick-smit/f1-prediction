<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\DTO;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Countable;
use Iterator;

abstract class TopX implements Iterator, Countable
{
    private int $position = 0;

    #[\Override]
    abstract public function count(): int;

    abstract public static function fromArray(array $drivers): TopX;

    /**
     * @param Driver[] $drivers
     * @throws AssertionFailedException
     */
    protected function __construct(
        public readonly array $drivers,
    ) {
        Assertion::allIsInstanceOf($drivers, Driver::class, 'Drivers must be type of '.Driver::class);
        Assertion::count($drivers, $this->count(), sprintf('The amount of drivers in a top %d must be equal to %d.', $this->count(), $this->count()));

        $uniqueDrivers = [];
        $uniqueTeams = [];
        foreach ($this->drivers as $driver) {
            Assertion::notInArray($driver->id, $uniqueDrivers, 'A top X cannot have the same driver more than once');
            $uniqueDrivers[] = $driver->id;

            $uniqueTeams[$driver->team->id] ??= 0;
            ++$uniqueTeams[$driver->team->id];
            Assertion::max($uniqueTeams[$driver->team->id], 2, 'A top X cannot contain a team more than twice');
        }
    }

    #[\Override]
    public function current(): Driver
    {
        return $this->drivers[$this->position];
    }

    #[\Override]
    public function next(): void
    {
        ++$this->position;
    }

    #[\Override]
    public function key(): mixed
    {
        return $this->position;
    }

    #[\Override]
    public function valid(): bool
    {
        return isset($this->drivers[$this->position]);
    }

    #[\Override]
    public function rewind(): void
    {
        $this->position = 0;
    }
}
