<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Authentication\RegisterUser;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController
{
    use DispatchesJobs;

    public function create(): Response
    {
        Password::defaults();
        return Inertia::render('Auth/Register');
    }

    public function store(RegisterRequest $request, RegisterUser $registerUser, ResponseFactory $responseFactory): RedirectResponse
    {
        $registerUser->handle(
            $request->post('name'),
            $request->post('email'),
            $request->post('password'),
        );

        return $responseFactory->redirectToRoute('verification.show');
    }
}
