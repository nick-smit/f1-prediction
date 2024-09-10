<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ResetLinkRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ForgotPasswordController
{
    public function requestNewPasswordForm(SessionManager $sessionManager): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => $sessionManager->get('status')
        ]);
    }

    public function resetLink(ResetLinkRequest $request, PasswordBrokerManager $passwordBrokerManager, Redirector $redirector, Translator $translator)
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = $passwordBrokerManager->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return $redirector->route('forgot-password')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [$translator->get($status)],
        ]);
    }

    public function resetPasswordForm(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->query('email'),
            'token' => $request->route('token'),
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request, PasswordBrokerManager $passwordBrokerManager, Hasher $hasher, Dispatcher $dispatcher, Redirector $redirector, Translator $translator): RedirectResponse
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $passwordBrokerManager->reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user) use ($request, $hasher, $dispatcher): void {
            $user->forceFill([
                'password' => $hasher->make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            $dispatcher->dispatch(new PasswordReset($user));
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status === Password::PASSWORD_RESET) {
            return $redirector->route('login')->with('status', $translator->get($status));
        }

        throw ValidationException::withMessages([
            'email' => [$translator->get($status)],
        ]);
    }
}
