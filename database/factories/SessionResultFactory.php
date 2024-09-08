<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Driver;
use App\Models\RaceSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionResult>
 */
class SessionResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        return [
            'race_session_id' => RaceSession::factory(),
            'p1_id' => Driver::factory(),
            'p2_id' => Driver::factory(),
            'p3_id' => Driver::factory(),
            'p4_id' => Driver::factory(),
            'p5_id' => Driver::factory(),
            'p6_id' => Driver::factory(),
            'p7_id' => Driver::factory(),
            'p8_id' => Driver::factory(),
            'p9_id' => Driver::factory(),
            'p10_id' => Driver::factory(),
        ];
    }
}
