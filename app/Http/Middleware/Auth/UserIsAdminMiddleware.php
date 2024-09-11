<?php

declare(strict_types=1);

namespace App\Http\Middleware\Auth;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;

class UserIsAdminMiddleware
{
    public function __construct(private readonly Redirector $redirector)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user instanceof User) {
            return $this->redirector->route('login');
        }

        if (!$user->is_admin) {
            return $this->redirector->route('home');
        }

        return $next($request);
    }
}
