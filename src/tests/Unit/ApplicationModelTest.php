<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_status_constants_are_defined(): void
    {
        $this->assertSame([
            Application::STATUS_PENDING,
            Application::STATUS_REVIEWED,
            Application::STATUS_SHORTLISTED,
            Application::STATUS_REJECTED,
            Application::STATUS_HIRED,
            Application::STATUS_WITHDRAWN,
        ], Application::STATUSES);
    }

    public function test_application_belongs_to_job_and_user(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $job = Job::factory()->create();

        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($application->job->is($job));
        $this->assertTrue($application->user->is($user));
    }
}
