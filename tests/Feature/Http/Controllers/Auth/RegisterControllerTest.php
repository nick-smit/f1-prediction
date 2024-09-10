<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    public function test_register_form_shows(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_a_guest_can_register(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Str0ngP4ssw0rd!sRequ!red',
            'password_confirmation' => 'Str0ngP4ssw0rd!sRequ!red',
        ]);

        $response->assertRedirect(route('home'));

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

    public function test_an_authenticated_user_cannot_register(): void
    {
        $user = User::factory()->create();

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->actingAs($user)->post(route('register'));

        $response->assertRedirect(route('home'));

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_username_cannot_be_blank(): void
    {
        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'name' => '',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field is required.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_username_must_be_a_string(): void
    {
        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'name' => 123,
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field must be a string.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_username_must_be_unique(): void
    {
        User::factory()->create(['name' => 'John Doe']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name has already been taken.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_email_cannot_be_blank(): void
    {
        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'email' => '',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field is required.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_username_must_be_a_valid_email_address(): void
    {
        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'email' => 'invalid-email-address',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field must be a valid email address.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'email' => 'john@example.com',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email has already been taken.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_password_cannot_be_empty(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => '',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field is required.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_8_characters(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => 'A1!a',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must be at least 8 characters.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_1_lowercase_character(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => '12345678!A',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_1_uppercase_character(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => '12345678!a',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_1_number_character(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => 'ABCDEFGH!a',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one number.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_password_must_not_be_compromised(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => 'Abcd1234!',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The given password has appeared in a data leak. Please choose a different password.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }

    public function test_a_password_must_equal_the_password_confirmation(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        \Illuminate\Support\Facades\Notification::fake();

        $passwordOne = 'tjFR2fVBALqjFfzT';
        $passwordTwo = 'X4IyQPvDjoDQFg59';

        $response = $this->postJson(route('register'), [
            'password' => $passwordOne,
            'password_confirmation' => $passwordTwo,
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field confirmation does not match.']);

        Bus::assertNothingDispatched();
        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }
}
