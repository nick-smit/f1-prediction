<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Authentication\AuthenticateUser;
use App\Actions\Authentication\SignUserOff;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticationController
{
    use DispatchesJobs;

    public function show(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request, AuthenticateUser $action, Redirector $redirector): RedirectResponse
    {
        $action->handle(
            $request->post('email'),
            $request->post('password'),
            $request->post('remember', false),
        );

        $request->session()->regenerate();

        return $redirector->intended();
    }

    public function logout(Redirector $redirector, SignUserOff $action): RedirectResponse
    {
        $action->handle();

        return $redirector->route('home');
    }
}
