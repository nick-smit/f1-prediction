<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

final class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_see_the_verify_email_page(): void
    {
        $response = $this->get(route('verification.show'));

        $response->assertRedirectToRoute('login');
    }

    public function test_a_verified_user_is_redirected_to_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('verification.show'));

        $response->assertRedirectToRoute('home');
    }

    public function test_an_unverified_user_sess_the_verification_page(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.show'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Auth/VerifyEmail');
        });
    }

    public function test_a_guest_cannot_verify_their_email(): void
    {
        $user = User::factory()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinute(),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->get($url);

        $response->assertRedirectToRoute('login');
    }

    public function test_a_verified_user_can_verify_their_email_again(): void
    {
        $user = User::factory()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinute(),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->actingAs($user)->get($url);

        $response->assertRedirectToRoute('home', ['verified' => 1]);
    }

    public function test_an_unverified_user_can_verify_their_email_again(): void
    {
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinute(),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->actingAs($user)->get($url);

        $response->assertRedirectToRoute('home', ['verified' => 1]);
        $this->assertInstanceOf(DateTimeInterface::class, $user->refresh()->email_verified_at);
    }

    public function test_a_guest_cannot_resend_the_verification_email(): void
    {
        $response = $this->post(route('verification.send'));

        $response->assertRedirectToRoute('login');
    }

    public function test_a_verified_user_cannot_resend_the_verification_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirectToRoute('home');
    }

    public function test_an_unverified_user_can_resend_the_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-link-sent');

        Notification::assertSentTo([$user], VerifyEmail::class);
    }
}
