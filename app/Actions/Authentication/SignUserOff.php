<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use Illuminate\Auth\AuthManager;
use Illuminate\Session\SessionManager;

readonly class SignUserOff
{
    public function __construct(
        private AuthManager $authManager,
        private SessionManager $sessionManager,
    ) {

    }

    /**
     * Execute the action.
     */
    public function handle(): void
    {
        $this->authManager->guard('web')->logout();

        $this->sessionManager->invalidate();
        $this->sessionManager->regenerateToken();
    }
}
