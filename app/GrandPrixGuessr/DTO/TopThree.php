<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\DTO;

use Assert\AssertionFailedException;

class TopThree extends TopX
{
    #[\Override]
    public function count(): int
    {
        return 3;
    }

    /**
     * @param  Driver[]  $drivers
     *
     * @throws AssertionFailedException
     */
    #[\Override]
    public static function fromArray(array $drivers): TopThree
    {
        return new self($drivers);
    }
}
