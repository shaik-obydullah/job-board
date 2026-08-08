<?php

namespace Tests\Feature;

use App\Livewire\Company\CompanySettings;
use App\Livewire\Company\JobApplicants;
use App\Livewire\Company\JobForm;
use App\Livewire\Company\MyJobs;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_create_company_via_settings()
    {
        $employer = User::factory()->create(['role' => 'employer']);

        Livewire::actingAs($employer)
            ->test(CompanySettings::class)
            ->set('name', 'TechCorp')
            ->set('website', 'https://techcorp.example')
            ->set('industry', 'Technology')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('companies', [
            'user_id' => $employer->id,
            'name' => 'TechCorp',
        ]);
    }

    public function test_employer_can_post_a_job()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['user_id' => $employer->id]);
        $category = JobCategory::create(['name' => 'Engineering', 'slug' => 'engineering']);

        Livewire::actingAs($employer)
            ->test(JobForm::class)
            ->set('title', 'Backend Developer')
            ->set('category_id', $category->id)
            ->set('description', 'We need a backend developer.')
            ->set('location_type', 'remote')
            ->set('job_type', 'full_time')
            ->set('experience_level', 'mid')
            ->set('salary_currency', 'USD')
            ->set('vacancies', 1)
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('jobs', [
            'company_id' => $company->id,
            'title' => 'Backend Developer',
            'status' => 'published',
        ]);
    }

    public function test_employer_can_save_a_draft_job()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['user_id' => $employer->id]);
        $category = JobCategory::create(['name' => 'Engineering', 'slug' => 'engineering']);

        Livewire::actingAs($employer)
            ->test(JobForm::class)
            ->set('title', 'Draft Role')
            ->set('category_id', $category->id)
            ->set('description', 'Not ready to publish yet.')
            ->set('location_type', 'on_site')
            ->set('job_type', 'full_time')
            ->set('experience_level', 'entry')
            ->set('salary_currency', 'USD')
            ->set('vacancies', 1)
            ->set('status', 'draft')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('jobs', [
            'title' => 'Draft Role',
            'status' => 'draft',
        ]);
    }

    public function test_employer_can_view_my_jobs()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['user_id' => $employer->id]);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $employer->id,
            'status' => 'published',
        ]);

        $response = $this->actingAs($employer)->get(route('company.jobs'));

        $response->assertOk();
        $response->assertSee($job->title);
    }

    public function test_employer_can_toggle_job_status()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['user_id' => $employer->id]);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $employer->id,
            'status' => 'draft',
        ]);

        Livewire::actingAs($employer)
            ->test(MyJobs::class)
            ->call('toggleStatus', $job->id)
            ->assertHasNoErrors();

        $this->assertEquals('published', $job->fresh()->status);
    }

    public function test_employer_can_see_applicants()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $seeker = User::factory()->create(['role' => 'job_seeker']);
        $company = Company::factory()->create(['user_id' => $employer->id]);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $employer->id,
            'status' => 'published',
        ]);
        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $seeker->id,
        ]);

        $response = $this->actingAs($employer)->get(route('company.jobs.applicants', $job));

        $response->assertOk();
        $response->assertSee($seeker->name);
    }

    public function test_employer_cannot_access_other_companys_applicants()
    {
        $owner = User::factory()->create(['role' => 'employer']);
        $other = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['user_id' => $owner->id]);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $owner->id,
        ]);

        $this->actingAs($other)
            ->get(route('company.jobs.applicants', $job))
            ->assertForbidden();
    }

    public function test_employer_can_update_application_status()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $seeker = User::factory()->create(['role' => 'job_seeker']);
        $company = Company::factory()->create(['user_id' => $employer->id]);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $employer->id,
            'status' => 'published',
        ]);
        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $seeker->id,
            'status' => 'pending',
        ]);

        Livewire::actingAs($employer)
            ->test(JobApplicants::class, ['job' => $job])
            ->call('setStatus', $application->id, 'shortlisted')
            ->assertHasNoErrors();

        $this->assertEquals('shortlisted', $application->fresh()->status);
    }
}
