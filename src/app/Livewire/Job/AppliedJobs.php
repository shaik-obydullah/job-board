<?php

namespace App\Livewire\Job;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Applied jobs')]
class AppliedJobs extends Component
{
    use WithPagination;

    public function withdraw(string $applicationId): void
    {
        $application = Application::where('user_id', auth()->id())->findOrFail($applicationId);

        if (! $application->job->isOpen()) {
            session()->flash('error', 'You can only withdraw applications for open jobs.');

            return;
        }

        $application->update(['status' => Application::STATUS_WITHDRAWN]);
        session()->flash('success', 'Application withdrawn.');
    }

    public function render()
    {
        $applications = Application::with(['job.company'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(9);

        return view('livewire.job.applied-jobs', ['applications' => $applications]);
    }
}
