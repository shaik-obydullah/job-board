<?php

namespace App\Livewire\Job;

use App\Models\Job;
use App\Models\JobCategory;
use App\Support\CacheKeys;
use App\Support\JobMeta;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Find jobs')]
class JobSearch extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $location = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $sort = 'latest';

    public array $types = [];

    public array $levels = [];

    public array $locationTypes = [];

    public int $maxSalary = 200000;

    #[Url]
    public int $salary = 0;

    public string $posted = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'location' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'latest'],
        'salary' => ['except' => 0],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedLocation(): void
    {
        $this->resetPage();
    }

    public function updatedTypes(): void
    {
        $this->resetPage();
    }

    public function updatedLevels(): void
    {
        $this->resetPage();
    }

    public function updatedLocationTypes(): void
    {
        $this->resetPage();
    }

    public function updatedSalary(): void
    {
        $this->resetPage();
    }

    public function updatedPosted(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'location', 'category', 'types', 'levels', 'locationTypes', 'salary', 'posted', 'sort']);
        $this->resetPage();
    }

    public function setCategory(string $slug): void
    {
        $this->category = $slug;
        $this->resetPage();
    }

    #[Computed]
    public function categories()
    {
        return Cache::remember(CacheKeys::ALL_CATEGORIES, 3600, function () {
            return JobCategory::withCount('jobs')->where('is_active', true)->orderBy('name')->get();
        });
    }

    public function render()
    {
        $query = Job::open()->with('company', 'category', 'skills');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('requirements', 'like', '%'.$this->search.'%')
                    ->orWhereHas('company', fn ($c) => $c->where('name', 'like', '%'.$this->search.'%'))
                    ->orWhereHas('skills', fn ($s) => $s->where('name', 'like', '%'.$this->search.'%'));
            });
        }

        if ($this->location) {
            $query->where(function ($q) {
                $q->where('location', 'like', '%'.$this->location.'%')
                    ->orWhereHas('company', fn ($c) => $c->where('location', 'like', '%'.$this->location.'%'));
            });
        }

        if ($this->category) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $this->category));
        }

        if ($this->types) {
            $query->whereIn('job_type', $this->types);
        }

        if ($this->levels) {
            $query->whereIn('experience_level', $this->levels);
        }

        if ($this->locationTypes) {
            $query->whereIn('location_type', $this->locationTypes);
        }

        if ($this->salary > 0) {
            $query->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->whereNull('salary_max')->where('salary_min', '>=', $this->salary);
                })->orWhere('salary_max', '>=', $this->salary);
            });
        }

        if ($this->posted) {
            $query->where('created_at', '>=', match ($this->posted) {
                'today' => now()->startOfDay(),
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                default => now()->startOfDay(),
            });
        }

        $query->when($this->sort === 'salary', fn ($q) => $q->orderByRaw('COALESCE(salary_max, salary_min, 0) DESC'))
            ->when($this->sort === 'oldest', fn ($q) => $q->oldest())
            ->when(! in_array($this->sort, ['salary', 'oldest']), fn ($q) => $q->latest());

        $jobs = $query->paginate(9);

        return view('livewire.job.job-search', [
            'jobs' => $jobs,
            'jobTypes' => JobMeta::jobTypes(),
            'experienceLevels' => JobMeta::experienceLevels(),
            'locationTypeOptions' => JobMeta::locationTypes(),
        ]);
    }
}
