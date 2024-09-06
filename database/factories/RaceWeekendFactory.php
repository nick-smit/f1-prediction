<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RaceWeekend>
 */
class RaceWeekendFactory extends Factory
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
            'start_date' => fake()->dateTimeThisYear('first day of next year'),
            'name' => fake()->country() . ' Grand Prix',
        ];
    }
}
