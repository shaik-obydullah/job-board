<?php

namespace App\Livewire\Company;

use App\Models\Application;
use App\Models\Job;
use App\Notifications\ApplicationStatusChanged;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Job Applicants')]
class JobApplicants extends Component
{
    use WithPagination;

    public Job $job;

    public string $statusFilter = '';

    public string $search = '';

    public ?string $showStatus = null;

    public ?string $employerNote = null;

    public ?Application $selectedApplication = null;

    public function mount(Job $job): void
    {
        if ($job->company_id !== auth()->user()->company?->id) {
            abort(403);
        }

        $this->job = $job;
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setStatus(Application $application, string $status): void
    {
        $application->update([
            'status' => $status,
            'employer_note' => $this->employerNote ?: $application->employer_note,
            'reviewed_at' => now(),
        ]);

        $application->user->notify(new ApplicationStatusChanged($application));

        $this->showStatus = null;
        $this->employerNote = null;

        session()->flash('success', 'Application status updated.');
    }

    public function openStatus(Application $application, string $status): void
    {
        $this->selectedApplication = $application;
        $this->showStatus = $status;
        $this->employerNote = $application->employer_note;
    }

    public function render()
    {
        $query = $this->job->applications()->with(['user', 'user.skills'])->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        $applications = $query->paginate(12);

        return view('livewire.company.job-applicants', [
            'applications' => $applications,
            'statuses' => Application::STATUSES,
            'counts' => [
                'all' => $this->job->applications()->count(),
                'pending' => $this->job->applications()->where('status', 'pending')->count(),
                'reviewed' => $this->job->applications()->where('status', 'reviewed')->count(),
                'shortlisted' => $this->job->applications()->where('status', 'shortlisted')->count(),
                'rejected' => $this->job->applications()->where('status', 'rejected')->count(),
            ],
        ]);
    }
}
