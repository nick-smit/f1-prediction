<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationController
{
    public function show(Request $request, Redirector $redirector): Response|RedirectResponse
    {
        /** @var MustVerifyEmail $user */
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return $redirector->intended('');
        }

        return Inertia::render('Auth/VerifyEmail');
    }

    public function verify(Request $request, Redirector $redirector, Dispatcher $dispatcher): RedirectResponse
    {
        /** @var MustVerifyEmail $user */
        $user = $request->user();
        if (!$user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
            $dispatcher->dispatch(new Verified($user));
        }

        return $redirector->intended('/?verified=1');
    }

    public function send(Request $request, Redirector $redirector): RedirectResponse
    {
        /** @var MustVerifyEmail $user */
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return $redirector->intended();
        }

        $user->sendEmailVerificationNotification();

        return $redirector->back()->with('status', 'verification-link-sent');
    }
}
