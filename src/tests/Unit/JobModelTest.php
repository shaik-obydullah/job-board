<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobModelTest extends TestCase
{
    use RefreshDatabase;

    private function makeJob(array $attributes = []): Job
    {
        $company = Company::factory()->create();

        return Job::factory()->create(array_merge([
            'company_id' => $company->id,
            'user_id' => $company->user_id,
            'category_id' => JobCategory::factory(),
        ], $attributes));
    }

    public function test_published_job_without_deadline_is_open(): void
    {
        $job = $this->makeJob(['status' => Job::STATUS_PUBLISHED, 'deadline' => null]);

        $this->assertTrue($job->isOpen());
    }

    public function test_published_job_with_future_deadline_is_open(): void
    {
        $job = $this->makeJob(['deadline' => now()->addWeek()->toDateString()]);

        $this->assertTrue($job->isOpen());
    }

    public function test_draft_job_is_not_open(): void
    {
        $job = $this->makeJob(['status' => Job::STATUS_DRAFT, 'deadline' => null]);

        $this->assertFalse($job->isOpen());
    }

    public function test_closed_job_is_not_open(): void
    {
        $job = $this->makeJob(['status' => Job::STATUS_CLOSED]);

        $this->assertFalse($job->isOpen());
    }

    public function test_expired_job_is_not_open_but_has_expired(): void
    {
        $job = $this->makeJob(['deadline' => now()->subDay()->toDateString()]);

        $this->assertFalse($job->isOpen());
        $this->assertTrue($job->hasExpired());
    }

    public function test_open_scope_only_returns_open_jobs(): void
    {
        $this->makeJob();
        $this->makeJob(['status' => Job::STATUS_DRAFT]);
        $this->makeJob(['deadline' => now()->subDay()->toDateString()]);
        $this->makeJob(['status' => Job::STATUS_CLOSED]);

        $this->assertEquals(1, Job::open()->count());
    }

    public function test_salary_label_returns_negotiable(): void
    {
        $job = $this->makeJob(['is_salary_negotiable' => true]);

        $this->assertEquals('Negotiable', $job->salary_label);
    }

    public function test_salary_label_formats_range(): void
    {
        $job = $this->makeJob([
            'is_salary_negotiable' => false,
            'salary_min' => 50000,
            'salary_max' => 70000,
            'salary_currency' => 'USD',
        ]);

        $this->assertEquals('USD 50,000 - USD 70,000', $job->salary_label);
    }

    public function test_salary_label_returns_not_disclosed_without_salary(): void
    {
        $job = $this->makeJob([
            'is_salary_negotiable' => false,
            'salary_min' => null,
            'salary_max' => null,
        ]);

        $this->assertEquals('Not disclosed', $job->salary_label);
    }

    public function test_register_view_increments_views_count(): void
    {
        $job = $this->makeJob(['views_count' => 10]);

        $job->registerView();

        $this->assertEquals(11, $job->fresh()->views_count);
    }
}
