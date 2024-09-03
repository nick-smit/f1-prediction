<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Darts\Settings\UserSettingsDTO;
use App\Models\Game;
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
}
