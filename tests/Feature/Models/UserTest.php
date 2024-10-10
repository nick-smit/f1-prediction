<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Prediction;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_model_casts_email_verified_at_to_datetime(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(DateTime::class, $user->email_verified_at);
    }

    public function test_a_user_can_have_predictions(): void
    {
        $user = User::factory()->create();
        $predictions = Prediction::factory()->count(10)->make(['user_id' => null]);

        $user->predictions()->saveMany($predictions);

        $user = $user->refresh();

        $this->assertDatabaseCount(Prediction::class, 10);
        $this->assertNotEmpty($user->predictions->toArray());
    }
}
