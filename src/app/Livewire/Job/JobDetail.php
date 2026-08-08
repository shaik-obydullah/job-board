<?php

namespace App\Livewire\Job;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Job;
use App\Notifications\ApplicationSubmitted;
use App\Notifications\NewApplication;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class JobDetail extends Component
{
    use WithPagination;

    public Job $job;

    public bool $showApplyModal = false;

    public string $coverLetter = '';

    public function mount(Job $job): void
    {
        $this->job = $job;
        $job->registerView();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $user = auth()->user();

        $relatedJobs = Job::open()
            ->with('company')
            ->where('id', '!=', $this->job->id)
            ->where(function ($q) {
                $q->where('category_id', $this->job->category_id)
                    ->orWhere('location_type', $this->job->location_type);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.job.job-detail', [
            'job' => $this->job,
            'relatedJobs' => $relatedJobs,
            'hasApplied' => $user ? Application::where('job_id', $this->job->id)->where('user_id', $user->id)->exists() : false,
            'isSaved' => $user ? $user->savedJobs()->where('job_id', $this->job->id)->exists() : false,
            'isFollowing' => $user ? $user->following()->where('company_id', $this->job->company_id)->exists() : false,
        ])->title($this->job->title);
    }

    public function openApplyModal(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));

            return;
        }

        if (! auth()->user()->isJobSeeker()) {
            session()->flash('error', 'Only job seekers can apply for jobs.');

            return;
        }

        if (! $this->job->isOpen()) {
            session()->flash('error', 'This job is no longer accepting applications.');

            return;
        }

        $this->showApplyModal = true;
    }

    public function apply(): void
    {
        $user = auth()->user();

        if (! $user || ! $user->isJobSeeker()) {
            return;
        }

        $this->validate(['coverLetter' => ['nullable', 'string', 'max:5000']]);

        $application = Application::firstOrCreate(
            ['job_id' => $this->job->id, 'user_id' => $user->id],
            ['cover_letter' => $this->coverLetter, 'status' => Application::STATUS_PENDING]
        );

        $this->showApplyModal = false;
        $this->coverLetter = '';

        ActivityLog::record($user, 'job_applied', "Applied to {$this->job->title} at {$this->job->company->name}.", [
            'job_id' => $this->job->id,
        ]);

        $this->job->company->user->notify(new NewApplication($this->job, $user));
        $user->notify(new ApplicationSubmitted($this->job));

        session()->flash('success', 'Your application was submitted successfully!');
    }

    public function toggleSave(): void
    {
        $user = auth()->user();

        if (! $user) {
            $this->redirect(route('login'));

            return;
        }

        if ($user->savedJobs()->where('job_id', $this->job->id)->exists()) {
            $user->savedJobs()->detach($this->job->id);
            session()->flash('success', 'Job removed from saved jobs.');
        } else {
            $user->savedJobs()->attach($this->job->id);
            session()->flash('success', 'Job saved successfully!');
        }
    }

    public function toggleFollow(): void
    {
        $user = auth()->user();

        if (! $user) {
            $this->redirect(route('login'));

            return;
        }

        if ($user->following()->where('company_id', $this->job->company_id)->exists()) {
            $user->following()->detach($this->job->company_id);
            session()->flash('success', 'You unfollowed this company.');
        } else {
            $user->following()->attach($this->job->company_id);
            session()->flash('success', 'You are now following this company!');
        }
    }

    public function paginationView(): string
    {
        return 'vendor.pagination.tailwind';
    }
}
