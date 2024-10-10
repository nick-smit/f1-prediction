<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\GrandPrixGuessr\Session\SessionType;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

final class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_is_ok(): void
    {
        $testResponse = $this->get(route('home'));

        $testResponse->assertStatus(200);
        $testResponse->assertInertia(static function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Home/Home')
                ->where('next_session', null);
        });
    }

    public function test_home_responds_with_the_next_session(): void
    {
        RaceSession::factory(3)
            ->has(RaceWeekend::factory()->state(['name' => 'Some GP']))
            ->qualification()
            ->sequence(
                ['session_start' => Carbon::yesterday()],
                ['session_start' => Carbon::tomorrow()],
                ['session_start' => Carbon::tomorrow()->addDay()],
            )->create();

        $testResponse = $this->get(route('home'));

        $testResponse->assertStatus(200);
        $testResponse->assertInertia(static function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Home/Home')
                ->has(
                    'next_session',
                    fn (AssertableInertia $page): AssertableJson => $page
                    ->whereType('id', 'integer')
                    ->whereType('race_weekend_name', 'string')
                    ->where('type', SessionType::Qualification)
                    ->where('session_start', Carbon::tomorrow()->jsonSerialize())
                );
        });
    }
}
