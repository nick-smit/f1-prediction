<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Prediction;
use App\Models\RaceSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Prediction>
 */
class PredictionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'race_session_id' => RaceSession::factory(),
            'user_id' => User::factory(),
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
            'score' => null,
        ];
    }

    public function drivers(Collection $drivers)
    {
        return $this->state([
            'p1_id' => $drivers->get(0),
            'p2_id' => $drivers->get(1),
            'p3_id' => $drivers->get(2),
            'p4_id' => $drivers->get(3),
            'p5_id' => $drivers->get(4),
            'p6_id' => $drivers->get(5),
            'p7_id' => $drivers->get(6),
            'p8_id' => $drivers->get(7),
            'p9_id' => $drivers->get(8),
            'p10_id' => $drivers->get(9),
        ]);
    }
}
