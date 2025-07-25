<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'         => $this->faker->sentence(6),
            'announcement'  => $this->faker->text(160),
            'body'          => $this->faker->paragraphs(5, true),
            'published_at'  => $this->faker->dateTimeBetween('-10 days', '+2 days'),
        ];
    }
}
