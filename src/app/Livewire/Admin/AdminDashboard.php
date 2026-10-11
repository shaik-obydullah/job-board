<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin Dashboard')]
class AdminDashboard extends Component
{
    public function render()
    {
        $stats = [
            'users' => User::count(),
            'job_seekers' => User::where('role', 'job_seeker')->count(),
            'employers' => User::where('role', 'employer')->count(),
            'companies' => Company::count(),
            'jobs' => Job::count(),
            'open_jobs' => Job::open()->count(),
            'applications' => Application::count(),
            'categories' => JobCategory::count(),
        ];

        $recentUsers = User::latest()->take(8)->get();
        $recentJobs = Job::with('company')->latest()->take(8)->get();
        $recentApplications = Application::with(['job', 'user'])->latest()->take(8)->get();

        $jobsByCategory = Job::selectRaw('category_id, count(*) as total')
            ->with('category')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        return view('livewire.admin.admin-dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentJobs' => $recentJobs,
            'recentApplications' => $recentApplications,
            'jobsByCategory' => $jobsByCategory,
        ]);
    }
}
