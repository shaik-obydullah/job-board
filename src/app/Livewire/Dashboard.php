<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\Job;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $this->redirectRoute('admin.dashboard');

            return view('livewire.dashboard.admin-redirect');
        }

        if ($user->isEmployer()) {
            return $this->employerDashboard();
        }

        return $this->jobSeekerDashboard();
    }

    private function jobSeekerDashboard()
    {
        $user = auth()->user();

        $applicationCount = Application::where('user_id', $user->id)->count();
        $savedCount = $user->savedJobs()->count();
        $profileCompletion = $user->profileCompletion();

        $recentApplications = Application::with('job.company')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recommendedJobs = Job::open()
            ->with('company', 'category')
            ->where(function ($query) use ($user) {
                $query->where('title', 'like', '%'.$user->headline.'%')
                    ->orWhereHas('skills', fn ($q) => $q->whereIn('skills.id', $user->skills()->pluck('skills.id')));
            })
            ->take(4)
            ->get();

        return view('livewire.dashboard.job-seeker', [
            'applicationCount' => $applicationCount,
            'savedCount' => $savedCount,
            'profileCompletion' => $profileCompletion,
            'recentApplications' => $recentApplications,
            'recommendedJobs' => $recommendedJobs,
        ]);
    }

    private function employerDashboard()
    {
        $company = auth()->user()->company;

        $jobCount = $company ? $company->jobs()->count() : 0;
        $applicationCount = $company
            ? Application::whereIn('job_id', $company->jobs()->pluck('jobs.id'))->count()
            : 0;
        $followerCount = $company?->followers()->count() ?? 0;
        $totalViews = $company ? $company->jobs()->sum('views_count') : 0;

        $recentApplications = $company
            ? Application::with(['job', 'user'])
                ->whereIn('job_id', $company->jobs()->pluck('jobs.id'))
                ->latest()
                ->take(6)
                ->get()
            : collect();

        return view('livewire.dashboard.employer', [
            'company' => $company,
            'jobCount' => $jobCount,
            'applicationCount' => $applicationCount,
            'followerCount' => $followerCount,
            'totalViews' => $totalViews,
            'recentApplications' => $recentApplications,
        ]);
    }

    private function adminDashboard()
    {
        return redirect()->route('admin.dashboard');
    }
}
