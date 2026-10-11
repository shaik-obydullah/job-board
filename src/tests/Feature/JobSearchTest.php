<?php

namespace Tests\Feature;

use App\Livewire\Job\JobDetail;
use App\Livewire\Job\JobSearch;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class JobSearchTest extends TestCase
{
    use RefreshDatabase;

    protected User $seeker;

    protected User $employer;

    protected Job $job;

    protected Job $secondJob;

    protected function setUp(): void
    {
        parent::setUp();

        $technology = JobCategory::create(['name' => 'Technology', 'slug' => 'technology']);
        JobCategory::create(['name' => 'Design', 'slug' => 'design']);

        $this->seeker = User::factory()->create(['role' => 'job_seeker']);
        $this->employer = User::factory()->create(['role' => 'employer']);

        $company = Company::factory()->create([
            'user_id' => $this->employer->id,
            'name' => 'Acme Inc',
        ]);

        $this->job = Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $this->employer->id,
            'category_id' => $technology->id,
            'title' => 'Senior Laravel Developer',
            'job_type' => 'full_time',
            'location_type' => 'remote',
            'experience_level' => 'senior',
            'salary_max' => 120000,
        ]);

        $this->secondJob = Job::factory()->create([
            'company_id' => $company->id,
            'user_id' => $this->employer->id,
            'category_id' => $technology->id,
            'title' => 'UI Designer',
            'job_type' => 'contract',
            'location_type' => 'hybrid',
            'experience_level' => 'mid',
        ]);
    }

    public function test_user_can_search_jobs()
    {
        $response = $this->actingAs($this->seeker)->get('/jobs?search=Laravel');

        $response->assertOk();
        $response->assertSee('Senior Laravel Developer');
    }

    public function test_user_can_filter_by_category()
    {
        $response = $this->actingAs($this->seeker)->get('/jobs?category=technology');

        $response->assertOk();
        $response->assertSee('Senior Laravel Developer');
        $response->assertSee('UI Designer');
    }

    public function test_user_can_filter_by_job_type()
    {
        Livewire::actingAs($this->seeker)
            ->test(JobSearch::class)
            ->set('types', ['full_time'])
            ->assertOk()
            ->assertSee('Senior Laravel Developer')
            ->assertDontSee('UI Designer');
    }

    public function test_user_can_filter_by_location_type()
    {
        Livewire::actingAs($this->seeker)
            ->test(JobSearch::class)
            ->set('locationTypes', ['remote'])
            ->assertOk()
            ->assertSee('Senior Laravel Developer')
            ->assertDontSee('UI Designer');
    }

    public function test_user_can_filter_by_experience_level()
    {
        Livewire::actingAs($this->seeker)
            ->test(JobSearch::class)
            ->set('levels', ['senior'])
            ->assertOk()
            ->assertSee('Senior Laravel Developer')
            ->assertDontSee('UI Designer');
    }

    public function test_user_can_save_a_job()
    {
        Livewire::actingAs($this->seeker)
            ->test(JobDetail::class, ['job' => $this->job])
            ->call('toggleSave')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('saved_jobs', [
            'user_id' => $this->seeker->id,
            'job_id' => $this->job->id,
        ]);
    }

    public function test_user_can_unsave_a_job()
    {
        $this->seeker->savedJobs()->attach($this->job->id);

        Livewire::actingAs($this->seeker)
            ->test(JobDetail::class, ['job' => $this->job])
            ->call('toggleSave')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('saved_jobs', [
            'user_id' => $this->seeker->id,
            'job_id' => $this->job->id,
        ]);
    }

    public function test_user_can_view_a_job()
    {
        $response = $this->actingAs($this->seeker)
            ->get(route('jobs.show', $this->job));

        $response->assertOk();
        $response->assertSee('Senior Laravel Developer');
        $this->assertGreaterThan(0, $this->job->fresh()->views_count);
    }

    public function test_user_can_apply_to_a_job()
    {
        Livewire::actingAs($this->seeker)
            ->test(JobDetail::class, ['job' => $this->job])
            ->call('apply')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('applications', [
            'job_id' => $this->job->id,
            'user_id' => $this->seeker->id,
            'status' => Application::STATUS_PENDING,
        ]);
    }

    public function test_user_can_apply_with_cover_letter()
    {
        Livewire::actingAs($this->seeker)
            ->test(JobDetail::class, ['job' => $this->job])
            ->set('coverLetter', 'I am the perfect fit for this role.')
            ->call('apply')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('applications', [
            'job_id' => $this->job->id,
            'user_id' => $this->seeker->id,
            'cover_letter' => 'I am the perfect fit for this role.',
        ]);
    }

    public function test_user_cannot_apply_twice()
    {
        Application::create([
            'job_id' => $this->job->id,
            'user_id' => $this->seeker->id,
            'status' => Application::STATUS_PENDING,
        ]);

        Livewire::actingAs($this->seeker)
            ->test(JobDetail::class, ['job' => $this->job])
            ->call('apply')
            ->assertHasNoErrors();

        $this->assertEquals(1, Application::where('job_id', $this->job->id)->count());
    }
}
