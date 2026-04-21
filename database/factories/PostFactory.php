<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

    $categories = Category::inRandomOrder()->limit(2)->pluck('name')->toArray();

        return [
            'title' => fake()->sentence(6), 
            'slug' => fake()->slug(),
            'content' => fake()->paragraphs(3, true), 
            'image_url' => fake()->imageUrl(640, 480, 'finance'),
            'read_time' => fake()->numberBetween(3, 15),
            'category'     => json_encode($categories), 
            'is_published' => fake()->boolean()
        ];
    }
}
