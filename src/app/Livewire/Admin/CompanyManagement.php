<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manage Companies')]
class CompanyManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $verificationFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedVerificationFilter(): void
    {
        $this->resetPage();
    }

    public function toggleVerified(int $id): void
    {
        $company = Company::findOrFail($id);
        $company->update(['is_verified' => ! $company->is_verified]);
        session()->flash('success', $company->is_verified ? "{$company->name} marked as verified." : "{$company->name} unverified.");
    }

    public function toggleActive(int $id): void
    {
        $company = Company::findOrFail($id);
        $company->update(['is_active' => ! $company->is_active]);
        session()->flash('success', $company->is_active ? "{$company->name} is now active." : "{$company->name} is now inactive.");
    }

    public function render()
    {
        $query = Company::query()->withCount(['jobs', 'followers'])->with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('industry', 'like', '%'.$this->search.'%')
                    ->orWhere('location', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->verificationFilter === 'verified') {
            $query->where('is_verified', true);
        } elseif ($this->verificationFilter === 'unverified') {
            $query->where('is_verified', false);
        }

        $companies = $query->latest()->paginate(15);

        return view('livewire.admin.company-management', ['companies' => $companies]);
    }
}
