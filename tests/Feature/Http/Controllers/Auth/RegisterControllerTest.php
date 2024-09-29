<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Actions\Authentication\RegisterUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
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
        $this->mock(RegisterUser::class)
            ->expects('handle')
            ->once()
            ->withArgs(
                fn ($name, $email, $password): bool => $name === 'John Doe' &&
                $email === 'john@example.com' &&
                $password === 'Str0ngP4ssw0rd!sRequ!red'
            );

        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Str0ngP4ssw0rd!sRequ!red',
            'password_confirmation' => 'Str0ngP4ssw0rd!sRequ!red',
        ]);

        $response->assertRedirect(route('verification.show'));
    }

    public function test_an_authenticated_user_cannot_register(): void
    {
        $user = User::factory()->create();

        Bus::fake();
        Notification::fake();

        $response = $this->actingAs($user)->post(route('register'));

        $response->assertRedirect(route('home'));

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_registration_username_cannot_be_blank(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'name' => '',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field is required.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_registration_username_must_be_a_string(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'name' => 123,
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field must be a string.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_registration_username_must_be_unique(): void
    {
        User::factory()->create(['name' => 'John Doe']);

        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name has already been taken.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_an_email_address_cannot_be_blank(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'email' => '',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field is required.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_an_email_address_must_be_a_valid_email_address(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'email' => 'invalid-email-address',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email field must be a valid email address.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_an_email_address_must_be_unique(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'email' => 'john@example.com',
        ]);

        $response->assertJsonValidationErrors(['email' => 'The email has already been taken.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_password_cannot_be_empty(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => '',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field is required.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_8_characters(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => 'A1!a',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must be at least 8 characters.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_1_lowercase_character(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => '12345678!A',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_1_uppercase_character(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => '12345678!a',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_password_must_have_at_least_1_number_character(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => 'ABCDEFGH!a',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field must contain at least one number.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_password_must_not_be_compromised(): void
    {
        Bus::fake();
        Notification::fake();

        $response = $this->postJson(route('register'), [
            'password' => 'Abcd1234!',
        ]);

        $response->assertJsonValidationErrors(['password' => 'The given password has appeared in a data leak. Please choose a different password.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }

    public function test_a_password_must_equal_the_password_confirmation(): void
    {
        Bus::fake();
        Notification::fake();

        $passwordOne = 'tjFR2fVBALqjFfzT';
        $passwordTwo = 'X4IyQPvDjoDQFg59';

        $response = $this->postJson(route('register'), [
            'password' => $passwordOne,
            'password_confirmation' => $passwordTwo,
        ]);

        $response->assertJsonValidationErrors(['password' => 'The password field confirmation does not match.']);

        Bus::assertNothingDispatched();
        Notification::assertNothingSent();
    }
}
