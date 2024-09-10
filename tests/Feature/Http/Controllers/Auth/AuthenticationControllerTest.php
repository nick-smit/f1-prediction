<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string REMEMBER_TOKEN_COOKIE_NAME = 'remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d';

    public function test_a_guest_cannot_logout(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirectToRoute('login');
    }

    public function test_an_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirectToRoute('home');
        $this->assertGuest();
    }

    public function test_a_guest_view_the_login_form(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }

    public function test_a_guest_can_login_with_a_valid_user(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password'
        ]);

        $response = $this->post(route('login'), [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirectToRoute('home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_a_guest_can_login_with_a_valid_user_and_be_remembered(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password'
        ]);

        $response = $this->post(route('login'), [
            'email' => 'john@example.com',
            'password' => 'password',
            'remember' => true,
        ]);

        $response->assertRedirectToRoute('home');
        $response->assertCookie(self::REMEMBER_TOKEN_COOKIE_NAME);
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->refresh()->remember_token);
    }

    public function test_a_guest_cannot_login_with_an_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password'
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'john@example.com',
            'password' => 'invalid',
        ]);

        $response->assertJsonValidationErrors(['email' => 'These credentials do not match our records.']);
        $this->assertGuest();
    }

    public function test_a_guest_cannot_login_with_an_invalid_email_address(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'password'
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'invalid@example.com',
            'password' => 'password',
        ]);

        $response->assertJsonValidationErrors(['email' => 'These credentials do not match our records.']);
        $this->assertGuest();
    }

    public function test_the_email_address_is_required(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => '',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field is required.']);
        $this->assertGuest();
    }

    public function test_the_email_address_must_be_an_email_address(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => 'not-an-email-address',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field must be a valid email address.']);
        $this->assertGuest();
    }

    public function test_the_password_is_required(): void
    {
        $response = $this->postJson(route('login'), [
            'password' => '',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field is required.']);
        $this->assertGuest();
    }

    public function test_rate_limiting_kicks_in_after_five_failed_attempts(): void
    {
        for ($i = 0; $i <= 5; ++$i) {
            $response = $this->postJson(route('login'), [
                'email' => 'john@doe.com',
                'password' => 'invalid',
            ]);
        }

        $response->assertJsonValidationErrors('email');
        $this->assertStringStartsWith('Too many login attempts. Please try again in', $response->json('errors.email')[0]);
    }
}
