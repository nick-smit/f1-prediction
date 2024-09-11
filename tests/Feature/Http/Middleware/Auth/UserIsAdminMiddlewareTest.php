<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware\Auth;

use App\Http\Middleware\Auth\UserIsAdminMiddleware;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

final class UserIsAdminMiddlewareTest extends TestCase
{
    public function test_an_unauthorized_request_redirects_to_login(): void
    {
        $request = new Request();
        $next = function (): void {};

        $middleware = $this->app->make(UserIsAdminMiddleware::class);

        /** @var RedirectResponse $response */
        $response = $middleware->handle($request, $next);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('http://localhost/login', $response->getTargetUrl());
    }

    public function test_a_non_priviliged_user_request_redirects_to_home(): void
    {
        $user = User::factory()->make();

        $request = new Request();
        $request->setUserResolver(fn () => $user);

        $next = function (): void {};

        $middleware = $this->app->make(UserIsAdminMiddleware::class);

        /** @var RedirectResponse $response */
        $response = $middleware->handle($request, $next);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('http://localhost', $response->getTargetUrl());
    }

    public function test_a_priviliged_user_can_view_the_resource(): void
    {
        $user = User::factory()->admin()->make();

        $request = new Request();
        $request->setUserResolver(fn () => $user);

        $next = fn () => response()->make();

        $middleware = $this->app->make(UserIsAdminMiddleware::class);

        $response = $middleware->handle($request, $next);

        $this->assertSame(200, $response->getStatusCode());
    }
}
