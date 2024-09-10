<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Bus\Dispatchable;
use SensitiveParameter;

class RegisterUserJob
{
    use Dispatchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $username,
        private readonly string $email,
        #[SensitiveParameter]
        private readonly string $password
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(AuthManager $authManager, Dispatcher $dispatcher): void
    {
        $user = new User([
            'name' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $user->save();

        $authManager->login($user);

        $dispatcher->dispatch(new Registered($user));
    }
}
