<?php

namespace App\Livewire\Home;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Home')]
class HomePage extends Component
{
    public string $search = '';

    public string $location = '';

    public function search(): void
    {
        $this->redirect(route('jobs.index', [
            'search' => $this->search ?: null,
            'location' => $this->location ?: null,
        ]));
    }

    public function render()
    {
        $featuredJobs = Job::open()
            ->with('company', 'category', 'skills')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $recentJobs = Job::open()
            ->with('company', 'category')
            ->latest()
            ->take(6)
            ->get();

        $categories = Cache::remember(CacheKeys::POPULAR_CATEGORIES, 3600, function () {
            return JobCategory::withCount('jobs')
                ->where('is_active', true)
                ->orderByDesc('jobs_count')
                ->take(12)
                ->get();
        });

        $companies = Cache::remember(CacheKeys::HOME_COMPANIES, 300, function () {
            return Company::withCount(['jobs', 'followers'])
                ->where('is_active', true)
                ->orderByDesc('followers_count')
                ->take(8)
                ->get();
        });

        $stats = Cache::remember(CacheKeys::HOME_STATS, 300, function () {
            return [
                'jobs' => Job::count(),
                'companies' => Company::count(),
                'users' => User::count(),
            ];
        });

        return view('livewire.home.home-page', [
            'featuredJobs' => $featuredJobs,
            'recentJobs' => $recentJobs,
            'categories' => $categories,
            'companies' => $companies,
            'stats' => $stats,
        ]);
    }
}
