<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyMetric>
 */
class DailyMetricFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->unique()->date(),
            'active_users' => fake()->numberBetween(10, 6565),
            'total_transactions' => fake()->numberBetween(5, 5041),
            'total_simulations' => fake()->numberBetween(1, 20145)
        ];
    }
}
