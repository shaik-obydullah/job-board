<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'job_id' => Job::inRandomOrder()->first()?->id ?? Job::factory(),
            'user_id' => User::jobSeeker()->inRandomOrder()->first()?->id ?? User::factory()->jobSeeker(),
            'cover_letter' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(['pending', 'reviewed', 'shortlisted', 'rejected']),
        ];
    }
}
