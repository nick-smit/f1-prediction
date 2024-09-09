<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\DTO;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Team
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(
        public int $id,
        public string $name,
    ) {
        Assertion::min($this->id, 0, 'Team id can not be less than 0');
        Assertion::notBlank($this->name, 'Team name can not be empty');
    }
}
