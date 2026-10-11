<?php

namespace App\Livewire\Job;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Saved jobs')]
class SavedJobs extends Component
{
    use WithPagination;

    public function remove(string $jobId): void
    {
        auth()->user()->savedJobs()->detach($jobId);
        session()->flash('success', 'Job removed from saved jobs.');
    }

    public function render()
    {
        $jobs = auth()->user()->savedJobs()
            ->with('company')
            ->latest()
            ->paginate(9);

        return view('livewire.job.saved-jobs', ['jobs' => $jobs]);
    }
}
