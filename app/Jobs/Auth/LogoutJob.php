<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Session\SessionManager;

class LogoutJob
{
    use Dispatchable;

    /**
     * Execute the job.
     */
    public function handle(
        AuthManager    $authManager,
        SessionManager $sessionManager,
    ): void {
        $authManager->guard('web')->logout();

        $sessionManager->invalidate();
        $sessionManager->regenerateToken();
    }
}
