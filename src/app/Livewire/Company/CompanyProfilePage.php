<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\Job;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfilePage extends Component
{
    use WithPagination;

    public Company $company;

    public function mount(Company $company): void
    {
        $this->company = $company;
    }

    public function toggleFollow(): void
    {
        $user = auth()->user();

        if (! $user) {
            $this->redirect(route('login'));

            return;
        }

        if ($user->following()->where('company_id', $this->company->id)->exists()) {
            $user->following()->detach($this->company->id);
            session()->flash('success', 'You unfollowed this company.');
        } else {
            $user->following()->attach($this->company->id);
            session()->flash('success', 'You are now following this company!');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $jobs = Job::open()
            ->with('company', 'category')
            ->where('company_id', $this->company->id)
            ->latest()
            ->paginate(9);

        return view('livewire.company.company-profile-page', [
            'company' => $this->company,
            'jobs' => $jobs,
            'followerCount' => $this->company->followers()->count(),
            'isFollowing' => auth()->user()?->following()->where('company_id', $this->company->id)->exists(),
        ])->title($this->company->name);
    }
}
