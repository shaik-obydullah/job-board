<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Companies')]
class CompanyDirectory extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $industry = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedIndustry(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'industry']);
        $this->resetPage();
    }

    #[Computed]
    public function industries()
    {
        return Company::select('industry')->distinct()->whereNotNull('industry')->orderBy('industry')->pluck('industry');
    }

    public function render()
    {
        $query = Company::withCount(['jobs', 'followers'])
            ->where('is_active', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('location', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->industry) {
            $query->where('industry', $this->industry);
        }

        $companies = $query->orderByDesc('followers_count')->paginate(12);

        return view('livewire.company.company-directory', ['companies' => $companies]);
    }
}
