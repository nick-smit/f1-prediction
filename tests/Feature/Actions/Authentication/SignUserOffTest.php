<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Authentication;

use App\Actions\Authentication\SignUserOff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(SignUserOff::class)]
final class SignUserOffTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_the_user_is_signed_off(): void
    {
        $this->actingAs(User::factory()->create());

        $this->app->make(SignUserOff::class)->handle();

        $this->assertGuest();
    }
}
