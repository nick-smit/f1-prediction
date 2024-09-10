<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Auth\Authenticator;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\Auth\LogoutJob;
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

    public function login(LoginRequest $request, Authenticator $authenticator, Redirector $redirector): RedirectResponse
    {
        $authenticator->authenticate(
            $request->post('email'),
            $request->post('password'),
            $request->post('remember', false),
        );

        $request->session()->regenerate();

        return $redirector->intended();
    }

    public function logout(Redirector $redirector): RedirectResponse
    {
        $this->dispatch(new LogoutJob());

        return $redirector->route('home');
    }
}
