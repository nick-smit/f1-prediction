<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Auth;

use App\Jobs\Auth\RegisterUserJob;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class RegisterUserJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_job(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        \Illuminate\Support\Facades\Bus::dispatchSync(new RegisterUserJob(
            'John Doe',
            'john@example.com',
            'Str0ngP4ssw0rd!sRequ!red'
        ));

        $this->assertDatabaseCount(User::class, 1);
        /** @var User $user */
        $user = \App\Models\User::query()->first();
        $this->assertSame('John Doe', $user->name);
        $this->assertSame('john@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertNull($user->remember_token);
        $this->assertTrue(Hash::check('Str0ngP4ssw0rd!sRequ!red', $user->password));

        $this->assertAuthenticated();

        \Illuminate\Support\Facades\Notification::assertSentTo([$user], VerifyEmail::class);
    }
}
