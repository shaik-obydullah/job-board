<?php

namespace Database\Factories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobCategory>
 */
class JobCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'slug' => fake()->unique()->slug(),
            'icon' => fake()->randomElement(['briefcase', 'code', 'heart', 'graduation-cap', 'chart', 'stethoscope', 'car', 'plane']),
            'is_active' => true,
        ];
    }
}
