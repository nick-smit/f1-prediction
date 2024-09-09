<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\Data\DriverDTOMap;

use App\GrandPrixGuessr\DTO\Driver as DriverDTO;
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
}
