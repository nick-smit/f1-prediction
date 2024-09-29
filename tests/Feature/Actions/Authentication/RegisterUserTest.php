<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Authentication;

use App\Actions\Authentication\RegisterUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_the_user(): void
    {
        $this->app->make(RegisterUser::class)->handle(
            'John Doe',
            'john@example.com',
            'Str0ngP4ssw0rd!sRequ!red'
        );

        $this->assertDatabaseCount(User::class, 1);
        /** @var User $user */
        $user = User::query()->first();
        $this->assertSame('John Doe', $user->name);
        $this->assertSame('john@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertNull($user->remember_token);
        $this->assertTrue(Hash::check('Str0ngP4ssw0rd!sRequ!red', $user->password));
    }

    public function test_it_authenticates_the_user(): void
    {
        $this->app->make(RegisterUser::class)->handle(
            'John Doe',
            'john@example.com',
            'Str0ngP4ssw0rd!sRequ!red'
        );

        $this->assertAuthenticated();
    }

    public function test_it_dispatches_the_registered_event(): void
    {
        Event::fake();

        $this->app->make(RegisterUser::class)->handle(
            'John Doe',
            'john@example.com',
            'Str0ngP4ssw0rd!sRequ!red'
        );

        Event::assertDispatched(Registered::class, fn (Registered $event): bool => 'john@example.com' === $event->user->email);
    }
}
