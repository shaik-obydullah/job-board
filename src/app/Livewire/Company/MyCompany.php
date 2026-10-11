<?php

namespace App\Livewire\Company;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('My Company')]
class MyCompany extends Component
{
    public function render()
    {
        $company = auth()->user()->company;

        if (! $company) {
            return view('livewire.company.my-company', ['company' => null]);
        }

        $jobCount = $company->jobs()->count();
        $activeJobs = $company->publishedJobs()->count();
        $followerCount = $company->followers()->count();
        $totalViews = $company->jobs()->sum('views_count');
        $applicationCount = Application::whereIn('job_id', $company->jobs()->pluck('jobs.id'))->count();

        return view('livewire.company.my-company', [
            'company' => $company,
            'jobCount' => $jobCount,
            'activeJobs' => $activeJobs,
            'followerCount' => $followerCount,
            'totalViews' => $totalViews,
            'applicationCount' => $applicationCount,
        ]);
    }
}
