<?php

declare(strict_types=1);

namespace App\Jobs\User;

use App\Models\User;
use App\Notifications\User\PromotedToAdmin;
use Illuminate\Foundation\Bus\Dispatchable;

readonly class PromoteToAdminJob
{
    use Dispatchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private User $user)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->user->is_admin) {
            return;
        }

        $this->user->is_admin = true;
        $this->user->save();

        $this->user->notify(new PromotedToAdmin());
    }
}
