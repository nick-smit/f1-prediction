<?php

declare(strict_types=1);

namespace App\Http\Resources;

use JsonSerializable;
use Override;

readonly class ActionResultResource implements JsonSerializable
{
    public function __construct(
        private bool $success,
        private string $message = '',
    ) {
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
        ];
    }
}
