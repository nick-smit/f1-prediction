<?php

declare(strict_types=1);

namespace Database\Factories;

use App\GrandPrixGuessr\Session\SessionType;
use App\Models\RaceWeekend;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RaceSession>
 */
class RaceSessionFactory extends Factory
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
            'race_weekend_id' => RaceWeekend::factory(),
            'guessable' => true,
            'session_start' => fake()->dateTime,
            'session_end' => fake()->dateTime,
            'type' => SessionType::Race,
        ];
    }
}
