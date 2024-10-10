<?php

declare(strict_types=1);

namespace Database\Factories;

use App\GrandPrixGuessr\Session\SessionType;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<RaceSession>
 */
class RaceSessionFactory extends Factory
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
            'race_weekend_id' => RaceWeekend::factory(),
            'guessable' => true,
            'session_start' => fake()->dateTime,
            'session_end' => fake()->dateTime,
            'type' => SessionType::Race,
        ];
    }

    public function qualification(): self
    {
        return $this->state(['type' => SessionType::Qualification]);
    }

    public function race(): self
    {
        return $this->state(['type' => SessionType::Race]);
    }
}
