<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Darts\Settings\UserSettingsDTO;
use App\Models\Game;
use App\Models\Guess;
use App\Models\ScoringGame;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_model_casts_email_verified_at_to_datetime(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\DateTime::class, $user->email_verified_at);
    }

    public function test_a_user_can_have_guesses(): void
    {
        $user = User::factory()->create();
        $guesses = Guess::factory()->count(10)->make(['user_id' => null]);

        $user->guesses()->saveMany($guesses);

        $user = $user->refresh();

        $this->assertDatabaseCount(Guess::class, 10);
        $this->assertNotEmpty($user->guesses->toArray());
    }
}
