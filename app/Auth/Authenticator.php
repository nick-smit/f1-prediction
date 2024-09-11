<?php

declare(strict_types=1);

namespace App\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

readonly class Authenticator
{
    public function __construct(private AuthManager $authManager, private Translator $translator, private Dispatcher $dispatcher, private Request $request)
    {
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(string $email, #[SensitiveParameter] string $password, bool $remember): void
    {
        $this->ensureIsNotRateLimited($email);

        if (!$this->authManager->attempt(['email' => $email, 'password' => $password], $remember)) {
            RateLimiter::hit($this->throttleKey($email));

            throw ValidationException::withMessages([
                'email' => $this->translator->get('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($email));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(string $email): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($email), 5)) {
            return;
        }

        $this->dispatcher->dispatch(new Lockout($this->request));

        $seconds = RateLimiter::availableIn($this->throttleKey($email));

        throw ValidationException::withMessages([
            'email' => $this->translator->get('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    private function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email) . '|' . $this->request->ip());
    }
}
