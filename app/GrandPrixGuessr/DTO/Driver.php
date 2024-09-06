<?php
declare(strict_types=1);

namespace App\GrandPrixGuessr\DTO;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Driver
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(
        public int $id,
        public string $name,
        public Team $team
    ) {
        Assertion::min($this->id, 0, 'Driver id can not be less than 0');
        Assertion::notBlank($this->name, 'Driver name can not be empty');
    }
}