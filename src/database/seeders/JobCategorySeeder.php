<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Accounting', 'briefcase', 'Financial record keeping and management'],
            ['Administrative', 'clipboard', 'Office and administrative support roles'],
            ['Advertising', 'megaphone', 'Advertising and campaign management'],
            ['Architecture', 'building', 'Architectural design and planning'],
            ['Arts & Design', 'palette', 'Creative arts and design professions'],
            ['Automotive', 'car', 'Vehicle maintenance, sales and repair'],
            ['Banking', 'bank', 'Banking and financial services'],
            ['Biotechnology', 'dna', 'Biotech research and development'],
            ['Construction', 'hard-hat', 'Building and construction trades'],
            ['Customer Service', 'headset', 'Customer support and relations'],
            ['Education', 'graduation-cap', 'Teaching and education'],
            ['Engineering', 'cog', 'Engineering disciplines'],
            ['Finance', 'chart', 'Finance and investment'],
            ['Food & Hospitality', 'utensils', 'Restaurant, hotel and hospitality'],
            ['Government', 'landmark', 'Public sector roles'],
            ['Healthcare', 'stethoscope', 'Medical and healthcare'],
            ['Human Resources', 'users', 'HR and talent management'],
            ['Information Technology', 'code', 'Software, infrastructure and IT'],
            ['Legal', 'scale', 'Legal and law'],
            ['Logistics & Supply Chain', 'truck', 'Freight, shipping and supply chain'],
            ['Marketing', 'trending', 'Marketing and communications'],
            ['Media & Communications', 'newspaper', 'Journalism and media'],
            ['Real Estate', 'home', 'Property and real estate'],
            ['Retail', 'shopping-bag', 'Retail and merchandising'],
            ['Sales', 'handshake', 'Sales and business development'],
            ['Security', 'shield', 'Security and protective services'],
            ['Telecommunications', 'radio', 'Telecom and networking'],
            ['Transportation', 'plane', 'Transport and travel'],
        ];

        foreach ($categories as [$name, $icon, $description]) {
            JobCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                compact('name', 'icon', 'description')
            );
        }
    }
}
