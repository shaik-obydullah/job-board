<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            JobCategorySeeder::class,
            SkillSeeder::class,
        ]);

        $this->createAdmin();
        $this->createDemoEmployer();
        $this->createDemoJobSeeker();
        $this->seedCompaniesAndJobs();
    }

    private function createAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@jobboard.test'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );
    }

    private function createDemoEmployer(): void
    {
        $employer = User::updateOrCreate(
            ['email' => 'employer@jobboard.test'],
            [
                'name' => 'TechCorp HR',
                'username' => 'techcorp',
                'password' => 'password',
                'role' => User::ROLE_EMPLOYER,
                'email_verified_at' => now(),
            ]
        );

        if (! $employer->company) {
            Company::create([
                'user_id' => $employer->id,
                'name' => 'TechCorp Inc.',
                'slug' => 'techcorp-inc',
                'description' => 'TechCorp is a leading software company building products that empower millions of users worldwide.',
                'website' => 'https://techcorp.example.com',
                'location' => 'San Francisco, CA',
                'industry' => 'Technology',
                'size' => '501-1000',
                'founded_year' => '2008',
                'email' => 'hr@techcorp.example.com',
                'phone' => '+1 555 0100',
                'mission' => 'To build technology that makes the world a better place.',
                'is_verified' => true,
                'is_active' => true,
            ]);
        }
    }

    private function createDemoJobSeeker(): void
    {
        $seeker = User::updateOrCreate(
            ['email' => 'seeker@jobboard.test'],
            [
                'name' => 'Jane Developer',
                'username' => 'jane-dev',
                'password' => 'password',
                'role' => User::ROLE_JOB_SEEKER,
                'email_verified_at' => now(),
                'headline' => 'Senior Full-Stack Developer',
                'phone' => '+1 555 0199',
                'location' => 'New York, NY',
                'date_of_birth' => '1992-04-15',
                'summary' => 'Passionate software engineer with 8+ years of experience building scalable web applications using Laravel and React.',
                'website' => 'https://jane.example.com',
                'linkedin' => 'https://linkedin.com/in/jane-dev',
                'github' => 'https://github.com/jane-dev',
            ]
        );

        $seeker->educations()->create([
            'school' => 'Stanford University',
            'degree' => 'Bachelor of Science',
            'field_of_study' => 'Computer Science',
            'start_date' => '2010-09-01',
            'end_date' => '2014-06-01',
        ]);

        $seeker->employments()->create([
            'job_title' => 'Senior Full-Stack Developer',
            'company_name' => 'Acme Software',
            'location' => 'New York, NY',
            'start_date' => '2020-03-01',
            'end_date' => null,
            'description' => 'Leading a team of 6 engineers building SaaS products.',
            'is_current' => true,
        ]);

        foreach (['Laravel', 'React', 'TypeScript', 'MySQL', 'Docker'] as $skillName) {
            $skill = Skill::where('name', $skillName)->first();
            if ($skill) {
                $seeker->skills()->attach($skill, ['proficiency' => 'expert']);
            }
        }

        $seeker->languages()->create(['name' => 'English', 'proficiency' => 'native']);
        $seeker->languages()->create(['name' => 'Spanish', 'proficiency' => 'professional_working']);

        $seeker->certifications()->create([
            'name' => 'AWS Certified Solutions Architect',
            'issuing_organization' => 'Amazon Web Services',
            'issue_date' => '2021-05-01',
        ]);

        $seeker->portfolios()->create([
            'title' => 'E-commerce Platform',
            'description' => 'Full-featured e-commerce platform built with Laravel.',
            'url' => 'https://github.com/jane-dev/ecommerce',
        ]);

        $seeker->awards()->create([
            'title' => 'Employee of the Year',
            'issuer' => 'Acme Software',
            'date' => '2023-12-01',
        ]);

        $seeker->hobbies()->create(['name' => 'Hiking']);
        $seeker->hobbies()->create(['name' => 'Photography']);

        $seeker->references()->create([
            'name' => 'John Smith',
            'job_title' => 'Engineering Manager',
            'company_name' => 'Acme Software',
            'email' => 'john@acme.example.com',
            'relationship' => 'Manager',
        ]);
    }

    private function seedCompaniesAndJobs(): void
    {
        $skills = Skill::pluck('id')->toArray();
        $categories = JobCategory::pluck('id', 'slug')->toArray();

        $companyData = [
            ['name' => 'CloudNine', 'industry' => 'Technology', 'location' => 'Seattle, WA', 'size' => '51-200'],
            ['name' => 'MediCorp', 'industry' => 'Healthcare', 'location' => 'Boston, MA', 'size' => '501-1000'],
            ['name' => 'FinEdge', 'industry' => 'Finance', 'location' => 'New York, NY', 'size' => '201-500'],
            ['name' => 'EduSpark', 'industry' => 'Education', 'location' => 'Austin, TX', 'size' => '11-50'],
            ['name' => 'RetailPlus', 'industry' => 'Retail', 'location' => 'Chicago, IL', 'size' => '1000+'],
        ];

        foreach ($companyData as $i => $data) {
            $employer = User::factory()->employer()->create([
                'name' => $data['name'].' HR',
                'username' => Str::slug($data['name']).'-hr',
            ]);

            $company = Company::create([
                'user_id' => $employer->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => fake()->paragraphs(3, true),
                'website' => 'https://'.Str::slug($data['name']).'.com',
                'location' => $data['location'],
                'industry' => $data['industry'],
                'size' => $data['size'],
                'founded_year' => (string) fake()->numberBetween(1995, 2022),
                'email' => 'hr@'.Str::slug($data['name']).'.com',
                'phone' => fake()->phoneNumber(),
                'mission' => fake()->sentence(),
                'is_verified' => true,
                'is_active' => true,
            ]);

            $jobsToCreate = fake()->numberBetween(3, 6);

            for ($j = 0; $j < $jobsToCreate; $j++) {
                $job = Job::factory()->create([
                    'company_id' => $company->id,
                    'user_id' => $employer->id,
                    'category_id' => fake()->randomElement(array_values($categories)),
                ]);

                $job->skills()->attach(fake()->randomElements($skills, fake()->numberBetween(2, 5)));
            }
        }

        // Demo employer posts a couple of jobs too
        $demoCompany = Company::where('slug', 'techcorp-inc')->first();
        if ($demoCompany) {
            $job = Job::factory()->featured()->create([
                'company_id' => $demoCompany->id,
                'user_id' => $demoCompany->user_id,
                'title' => 'Senior Laravel Developer',
                'category_id' => $categories['information-technology'] ?? null,
            ]);
            $job->skills()->attach(Skill::whereIn('name', ['Laravel', 'PHP', 'MySQL', 'Redis'])->pluck('id'));
        }

        // Applications from demo seeker + factory seekers
        $seekers = User::jobSeeker()->where('email', '!=', 'seeker@jobboard.test')->limit(30)->get();
        $openJobs = Job::open()->limit(15)->get();

        foreach ($openJobs as $job) {
            $count = fake()->numberBetween(1, 4);
            $candidates = $seekers->shuffle()->take($count);

            foreach ($candidates as $seeker) {
                Application::firstOrCreate(
                    ['job_id' => $job->id, 'user_id' => $seeker->id],
                    [
                        'cover_letter' => fake()->paragraphs(2, true),
                        'status' => fake()->randomElement(['pending', 'reviewed', 'shortlisted', 'rejected']),
                    ]
                );
            }
        }

        // Demo seeker applies to a couple of jobs
        $demoSeeker = User::where('email', 'seeker@jobboard.test')->first();
        if ($demoSeeker && $openJobs->isNotEmpty()) {
            foreach ($openJobs->take(2) as $job) {
                Application::firstOrCreate(
                    ['job_id' => $job->id, 'user_id' => $demoSeeker->id],
                    ['cover_letter' => 'I am excited to apply for this position. My experience and skills align perfectly with the requirements.', 'status' => 'pending']
                );
            }
        }

        ActivityLog::record(User::where('email', 'admin@jobboard.test')->first(), 'system_seeded', 'Database seeded with demo data.');
    }
}
