<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'max_students' => $this->faker->numberBetween(10, 100),
            'final_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'type' => $this->faker->randomElement(['online', 'presencial']),
        ];
    }
}
