<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school' => fake()->randomElement(['MIT', 'Stanford University', 'University of California, Berkeley', 'Harvard University', 'Georgia Tech', 'Oxford University']),
            'degree' => fake()->randomElement(['Bachelor of Science', 'Master of Science', 'Bachelor of Arts', 'PhD', 'Associate Degree']),
            'field_of_study' => fake()->randomElement(['Computer Science', 'Business Administration', 'Electrical Engineering', 'Finance', 'Marketing', 'Data Science']),
            'start_date' => fake()->dateTimeBetween('-12 years', '-5 years')->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
            'description' => fake()->paragraph(),
            'is_current' => false,
        ];
    }
}
