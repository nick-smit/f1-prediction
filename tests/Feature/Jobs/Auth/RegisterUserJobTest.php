<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Auth;

use App\Jobs\Auth\RegisterUserJob;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class RegisterUserJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_job(): void
    {
        Notification::fake();

        Bus::dispatchSync(new RegisterUserJob(
            'John Doe',
            'john@example.com',
            'Str0ngP4ssw0rd!sRequ!red'
        ));

        $this->assertDatabaseCount(User::class, 1);
        /** @var User $user */
        $user = User::query()->first();
        $this->assertSame('John Doe', $user->name);
        $this->assertSame('john@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertNull($user->remember_token);
        $this->assertTrue(Hash::check('Str0ngP4ssw0rd!sRequ!red', $user->password));

        $this->assertAuthenticated();

        Notification::assertSentTo([$user], VerifyEmail::class);
    }
}
