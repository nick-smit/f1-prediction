<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

final class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_form_can_be_viewed(): void
    {
        $response = $this->get(route('forgot-password'));

        $response->assertOk();
    }

    public function test_an_authenticated_user_cannot_view_the_forgot_password_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('forgot-password'));

        $response->assertRedirectToRoute('home');
    }

    public function test_a_reset_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'john@example.com']);

        $response = $this->postJson(route('forgot-password'), [
            'email' => 'john@example.com'
        ]);

        $response->assertRedirectToRoute('forgot-password');
        $response->assertSessionHas('status', 'We have emailed your password reset link.');

        $this->assertDatabaseCount('password_reset_tokens', 1);

        Notification::assertSentTo([$user], ResetPassword::class);
    }

    public function test_forgot_password_the_email_field_cannot_be_blank(): void
    {
        $response = $this->postJson(route('forgot-password'), [
            'email' => '',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field is required.']);
    }

    public function test_forgot_password_the_email_field_must_contain_an_email(): void
    {
        $response = $this->postJson(route('forgot-password'), [
            'email' => 'not-an-email',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field must be a valid email address.']);
    }

    public function test_forgot_password_cannot_be_done_for_an_invalid_user(): void
    {
        $response = $this->postJson(route('forgot-password'), [
            'email' => 'invalid-user@example.com',
        ]);

        $response->assertJsonValidationErrors(['email' => "We can't find a user with that email address."]);
    }

    public function test_forgot_password_reset_links_are_throttled(): void
    {
        User::factory()->create(['email' => 'john@example.com']);
        $this->postJson(route('forgot-password'), [
            'email' => 'john@example.com',
        ]);
        $response = $this->postJson(route('forgot-password'), [
            'email' => 'john@example.com',
        ]);

        $response->assertJsonValidationErrors(['email' => 'Please wait before retrying.']);
    }

    public function test_the_password_reset_form_can_be_rendered(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'token', 'email' => 'john@example.com']));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Auth/ResetPassword');
            $assertableInertia->where('token', 'token');
            $assertableInertia->where('email', 'john@example.com');
        });
    }

    public function test_a_password_can_be_reset(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com'
        ]);

        $broker = $this->app->make(PasswordBrokerManager::class)->broker();
        $token = $broker->createToken($user);

        $response = $this->postJson(route('password.store', [
            'token' => $token,
            'email' => 'john@example.com',
            'password' => 'Str0ngP4ssw0rd!sRequ!red',
            'password_confirmation' => 'Str0ngP4ssw0rd!sRequ!red'
        ]));

        $response->assertRedirectToRoute('login');
        $this->assertTrue(Hash::check('Str0ngP4ssw0rd!sRequ!red', $user->refresh()->password));
    }

    public function test_a_password_cannot_be_reset_with_an_invalid_token(): void
    {
        User::factory()->create([
            'email' => 'john@example.com'
        ]);

        $response = $this->postJson(route('password.store', [
            'token' => 'invalid-token',
            'email' => 'john@example.com',
            'password' => 'Str0ngP4ssw0rd!sRequ!red',
            'password_confirmation' => 'Str0ngP4ssw0rd!sRequ!red'
        ]));

        $response->assertJsonValidationErrors(['email' => 'This password reset token is invalid.']);
    }

    public function test_a_password_cannot_be_reset_with_an_email_address_of_another_user(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com'
        ]);

        User::factory()->create([
            'email' => 'another-user@example.com'
        ]);

        $broker = $this->app->make(PasswordBrokerManager::class)->broker();
        $token = $broker->createToken($user);

        $response = $this->postJson(route('password.store', [
            'token' => $token,
            'email' => 'another-user@example.com',
            'password' => 'Str0ngP4ssw0rd!sRequ!red',
            'password_confirmation' => 'Str0ngP4ssw0rd!sRequ!red'
        ]));

        $response->assertJsonValidationErrors(['email' => 'This password reset token is invalid.']);
    }

    public function test_a_password_cannot_be_reset_without_a_token(): void
    {
        $response = $this->postJson(route('password.store', ['token' => '']));

        $response->assertJsonValidationErrors(['token' => 'The token field is required.']);
    }

    public function test_a_password_cannot_be_reset_without_an_email_address(): void
    {
        $response = $this->postJson(route('password.store', ['email' => '']));

        $response->assertJsonValidationErrors(['email' => 'The email field is required.']);
    }

    public function test_a_password_cannot_be_reset_with_an_invalid_email_address(): void
    {
        $response = $this->postJson(route('password.store', ['email' => 'not-an-email-address']));

        $response->assertJsonValidationErrors(['email' => 'The email field must be a valid email address.']);
    }

    public function test_a_password_cannot_be_empty(): void
    {
        $response = $this->postJson(route('password.store', ['password' => '']));

        $response->assertJsonValidationErrors(['password' => 'The password field is required.']);
    }

    public function test_a_password_must_have_at_least_8_characters(): void
    {
        $response = $this->postJson(route('password.store', ['password' => '123']));

        $response->assertJsonValidationErrors(['password' => 'The password field must be at least 8 characters.']);
    }

    public function test_a_password_must_have_at_least_1_lowercase_character(): void
    {

        $response = $this->postJson(route('password.store', ['password' => '12345678!A']));

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);
    }

    public function test_a_password_must_have_at_least_1_uppercase_character(): void
    {

        $response = $this->postJson(route('password.store', ['password' => '12345678!a']));

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);
    }

    public function test_a_password_must_have_at_least_1_number_character(): void
    {

        $response = $this->postJson(route('password.store', ['password' => 'ABCDEFGH!a']));

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one number.']);
    }

    public function test_a_password_must_not_be_compromised(): void
    {

        $response = $this->postJson(route('password.store', ['password' => 'Abcd1234!']));

        $response->assertJsonValidationErrors(['password' => 'The given password has appeared in a data leak. Please choose a different password.']);
    }

    public function test_a_password_must_equal_the_password_confirmation(): void
    {
        $passwordOne = 'tjFR2fVBALqjFfzT';
        $passwordTwo = 'X4IyQPvDjoDQFg59';

        $response = $this->postJson(route('password.store', [
            'password' => $passwordOne,
            'password_confirmation' => $passwordTwo
        ]));

        $response->assertJsonValidationErrors(['password' => 'The password field confirmation does not match.']);

    }
}
