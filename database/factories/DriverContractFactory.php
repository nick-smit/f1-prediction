<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DriverContract>
 */
class DriverContractFactory extends Factory
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
            'driver_id' => Driver::factory(),
            'team_id' => Team::factory(),
            'start_date' => fake()->dateTimeThisDecade,
            'end_date' => null,
        ];
    }
}
