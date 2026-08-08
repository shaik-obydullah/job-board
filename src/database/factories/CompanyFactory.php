<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'user_id' => User::factory()->employer(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'description' => fake()->paragraphs(3, true),
            'website' => 'https://'.Str::slug($name).'.com',
            'location' => fake()->city().', '.fake()->country(),
            'industry' => fake()->randomElement(['Technology', 'Finance', 'Healthcare', 'Education', 'Retail', 'Marketing', 'Manufacturing', 'Consulting']),
            'size' => fake()->randomElement(['1-10', '11-50', '51-200', '201-500', '501-1000', '1000+']),
            'founded_year' => (string) fake()->numberBetween(1990, 2024),
            'email' => 'hr@'.Str::slug($name).'.com',
            'phone' => fake()->phoneNumber(),
            'mission' => fake()->sentence(),
            'is_verified' => fake()->boolean(60),
            'is_active' => true,
        ];
    }
}
