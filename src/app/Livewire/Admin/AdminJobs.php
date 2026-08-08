<?php

namespace App\Livewire\Admin;

use App\Models\Job;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manage Jobs')]
class AdminJobs extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $id, string $status): void
    {
        if (! in_array($status, [Job::STATUS_DRAFT, Job::STATUS_PUBLISHED, Job::STATUS_CLOSED], true)) {
            return;
        }

        $job = Job::findOrFail($id);
        $job->update(['status' => $status]);
        session()->flash('success', "Job status updated to {$status}.");
    }

    public function render()
    {
        $query = Job::with(['company', 'category'])->withCount('applications');

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $jobs = $query->latest()->paginate(15);

        return view('livewire.admin.admin-jobs', ['jobs' => $jobs]);
    }
}
