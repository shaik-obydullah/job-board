<?php

namespace Database\Factories;

use App\Models\Employment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employment>
 */
class EmploymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'job_title' => fake()->jobTitle(),
            'company_name' => fake()->company(),
            'location' => fake()->city(),
            'start_date' => fake()->dateTimeBetween('-10 years', '-3 years')->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('-3 years', '-1 month')->format('Y-m-d'),
            'description' => fake()->paragraphs(2, true),
            'is_current' => false,
        ];
    }
}
