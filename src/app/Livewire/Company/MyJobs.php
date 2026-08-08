<?php

namespace App\Livewire\Company;

use App\Models\Job;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('My Jobs')]
class MyJobs extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public string $search = '';

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        $job = Job::findOrFail($id);
        $job->update([
            'status' => $job->status === Job::STATUS_PUBLISHED ? Job::STATUS_CLOSED : Job::STATUS_PUBLISHED,
        ]);

        session()->flash('success', $job->status === Job::STATUS_PUBLISHED
            ? 'Job published successfully.'
            : 'Job closed.');
    }

    public function duplicate(int $id): void
    {
        $job = Job::findOrFail($id);
        $copy = $job->replicate();
        $copy->title = $job->title.' (Copy)';
        $copy->slug = $job->slug.'-'.substr(md5((string) mt_rand()), 0, 5);
        $copy->status = Job::STATUS_DRAFT;
        $copy->views_count = 0;
        $copy->save();
        $copy->skills()->sync($job->skills()->pluck('skills.id'));

        session()->flash('success', 'Job duplicated as draft.');
    }

    public function render()
    {
        $query = Job::with(['category', 'applications', 'company'])
            ->where('company_id', auth()->user()->company?->id ?? 0);

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        $jobs = $query->latest()->paginate(10);

        return view('livewire.company.my-jobs', ['jobs' => $jobs]);
    }
}
