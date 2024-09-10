<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\Auth\RegisterRequest;
use App\Jobs\Auth\RegisterUserJob;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Bus\DispatchesJobs;
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

    public function store(RegisterRequest $request, ResponseFactory $responseFactory)
    {
        $this->dispatch(new RegisterUserJob(
            $request->post('name'),
            $request->post('email'),
            $request->post('password'),
        ));

        return $responseFactory->redirectToRoute('home');
    }
}
