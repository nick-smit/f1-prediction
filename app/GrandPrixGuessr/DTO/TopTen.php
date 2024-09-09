<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\DTO;

use Override;

class TopTen extends TopX
{
    #[Override]
    public function count(): int
    {
        return 10;
    }

    #[Override]
    public static function fromArray(array $drivers): self
    {
        return new self($drivers);
    }
}
