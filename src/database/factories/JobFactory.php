<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Job>
 */
class JobFactory extends Factory
{
    public function definition(): array
    {
        $company = Company::inRandomOrder()->first() ?? Company::factory()->create();
        $title = fake()->unique()->jobTitle();

        return [
            'company_id' => $company->id,
            'user_id' => $company->user_id,
            'category_id' => JobCategory::inRandomOrder()->first()?->id ?? JobCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(5)),
            'description' => fake()->paragraphs(4, true),
            'requirements' => '- '.collect(fake()->sentences(4))->implode("\n- "),
            'responsibilities' => '- '.collect(fake()->sentences(4))->implode("\n- "),
            'benefits' => '- '.collect(fake()->sentences(3))->implode("\n- "),
            'salary_min' => fake()->numberBetween(30000, 80000),
            'salary_max' => fake()->numberBetween(80000, 180000),
            'salary_currency' => 'USD',
            'is_salary_negotiable' => false,
            'location' => $company->location ?? fake()->city(),
            'location_type' => fake()->randomElement(['on_site', 'remote', 'hybrid']),
            'job_type' => fake()->randomElement(['full_time', 'part_time', 'contract', 'internship', 'freelance']),
            'experience_level' => fake()->randomElement(['entry', 'junior', 'mid', 'senior', 'lead']),
            'vacancies' => fake()->numberBetween(1, 5),
            'status' => 'published',
            'deadline' => fake()->dateTimeBetween('now', '+60 days')->format('Y-m-d'),
            'views_count' => fake()->numberBetween(0, 500),
            'is_featured' => fake()->boolean(15),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft', 'deadline' => null]);
    }

    public function closed(): static
    {
        return $this->state(fn () => ['status' => 'closed']);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
