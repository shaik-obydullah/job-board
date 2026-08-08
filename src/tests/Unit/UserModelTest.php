<?php

namespace Tests\Unit;

use App\Models\Award;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Employment;
use App\Models\Hobby;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Portfolio;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserReference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_helpers(): void
    {
        $seeker = User::factory()->jobSeeker()->create();
        $employer = User::factory()->employer()->create();
        $admin = User::factory()->admin()->create();

        $this->assertTrue($seeker->isJobSeeker());
        $this->assertFalse($seeker->isEmployer());
        $this->assertFalse($seeker->isAdmin());

        $this->assertTrue($employer->isEmployer());
        $this->assertFalse($employer->isAdmin());

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isJobSeeker());
    }

    public function test_profile_completion_returns_zero_for_empty_profile(): void
    {
        $user = User::factory()->create([
            'photo' => null,
            'headline' => null,
            'summary' => null,
            'location' => null,
            'phone' => null,
            'date_of_birth' => null,
            'address' => null,
            'website' => null,
            'linkedin' => null,
            'github' => null,
        ]);

        $this->assertSame(0, $user->profileCompletion());
    }

    public function test_profile_completion_is_capped_at_one_hundred(): void
    {
        $user = User::factory()->create([
            'photo' => 'avatar.jpg',
            'headline' => 'Senior Developer',
            'summary' => 'Experienced developer.',
            'location' => 'Berlin',
            'phone' => '+491234567890',
            'date_of_birth' => '1990-01-01',
            'address' => 'Main St 1',
            'website' => 'https://example.com',
            'linkedin' => 'https://linkedin.com/in/jane',
            'github' => 'https://github.com/jane',
        ]);

        Education::create(['user_id' => $user->id, 'school' => 'TU Berlin', 'degree' => 'BSc', 'is_current' => true]);
        Employment::create(['user_id' => $user->id, 'job_title' => 'Developer', 'company_name' => 'Acme', 'is_current' => true]);
        Skill::create(['name' => 'PHP', 'slug' => 'php']);
        $user->skills()->attach(Skill::first()->id, ['proficiency' => 5]);
        Language::create(['user_id' => $user->id, 'name' => 'English', 'proficiency' => 'native']);
        Certification::create(['user_id' => $user->id, 'name' => 'Laravel Certified']);
        Portfolio::create(['user_id' => $user->id, 'title' => 'My project']);
        Award::create(['user_id' => $user->id, 'title' => 'Employee of the year']);
        Membership::create(['user_id' => $user->id, 'organization' => 'PHP Berlin']);
        Hobby::create(['user_id' => $user->id, 'name' => 'Climbing']);
        UserReference::create(['user_id' => $user->id, 'name' => 'John Doe']);

        $this->assertSame(100, $user->profileCompletion());
    }
}
