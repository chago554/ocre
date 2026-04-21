<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportMessage>
 */
class SupportMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [            
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'subject' => fake()->text(50),
            'body' => fake()->paragraph(),
            'is_resolved' => fake()->boolean()

        ];
    }
}
