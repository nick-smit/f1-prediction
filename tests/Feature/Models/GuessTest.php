<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Driver;
use App\Models\Guess;
use App\Models\RaceSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class GuessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guess_can_be_created(): void
    {
        $user = User::factory()->create();
        $drivers = Driver::factory()->count(10)->create();
        $session = RaceSession::factory()->create();

        $guess = new Guess();

        $guess->user()->associate($user);
        $guess->raceSession()->associate($session);
        $guess->p1()->associate($drivers[0]);
        $guess->p2()->associate($drivers[1]);
        $guess->p3()->associate($drivers[2]);
        $guess->p4()->associate($drivers[3]);
        $guess->p5()->associate($drivers[4]);
        $guess->p6()->associate($drivers[5]);
        $guess->p7()->associate($drivers[6]);
        $guess->p8()->associate($drivers[7]);
        $guess->p9()->associate($drivers[8]);
        $guess->p10()->associate($drivers[9]);

        $success = $guess->save();

        $this->assertTrue($success);
        $this->assertDatabaseCount(Guess::class, 1);
    }
}
