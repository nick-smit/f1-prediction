<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use SensitiveParameter;

class RegisterUser
{
    public function __construct(
        private readonly AuthManager $authManager,
        private readonly Dispatcher  $dispatcher
    ) {

    }

    /**
     * Execute the action.
     */
    public function handle(
        string $username,
        string $email,
        #[SensitiveParameter]
        string $password
    ): void {
        $user = new User([
            'name' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        $user->save();

        $this->authManager->login($user);

        $this->dispatcher->dispatch(new Registered($user));
    }
}
