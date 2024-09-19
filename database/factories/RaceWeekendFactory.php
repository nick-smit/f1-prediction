<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RaceWeekend;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<RaceWeekend>
 */
class RaceWeekendFactory extends Factory
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
            'start_date' => fake()->dateTimeThisYear(),
            'name' => fake()->country() . ' Grand Prix',
            'stats_f1_name' => fake()->country(),
        ];
    }
}
