<?php

declare(strict_types=1);

namespace App\Actions\Authorization;

use App\Models\User;
use App\Notifications\User\PromotedToAdmin;

class PromoteUserToAdmin
{
    /**
     * Execute the action.
     */
    public function handle(User $user): void
    {
        if ($user->is_admin) {
            return;
        }

        $user->is_admin = true;
        $user->save();

        $user->notify(new PromotedToAdmin());
    }
}
