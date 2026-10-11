<?php

namespace App\Livewire\Company;

use App\Models\ActivityLog;
use App\Models\Company;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Company Settings')]
class CompanySettings extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $website = '';

    public string $location = '';

    public string $industry = '';

    public string $size = '';

    public string $founded_year = '';

    public string $email = '';

    public string $phone = '';

    public string $description = '';

    public string $mission = '';

    public $logo;

    public string $existingLogo = '';

    public function mount(): void
    {
        $company = auth()->user()->company;

        if (! $company) {
            return;
        }

        $this->name = $company->name;
        $this->website = $company->website ?? '';
        $this->location = $company->location ?? '';
        $this->industry = $company->industry ?? '';
        $this->size = $company->size ?? '';
        $this->founded_year = $company->founded_year ?? '';
        $this->email = $company->email ?? '';
        $this->phone = $company->phone ?? '';
        $this->description = $company->description ?? '';
        $this->mission = $company->mission ?? '';
        $this->existingLogo = $company->logo ?? '';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:50'],
            'founded_year' => ['nullable', 'digits:4'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $company = auth()->user()->company;

        $isUpdate = (bool) $company;

        $logoPath = $this->existingLogo;

        if ($this->logo) {
            $logoPath = $this->logo->store('companies/logos', 'public');
            $this->existingLogo = $logoPath;
        }

        if ($company) {
            $company->update([
                'name' => $this->name,
                'slug' => $company->slug ?? Str::slug($this->name).'-'.Str::lower(Str::random(5)),
                'logo' => $logoPath,
                'website' => $this->website ?: null,
                'location' => $this->location ?: null,
                'industry' => $this->industry ?: null,
                'size' => $this->size ?: null,
                'founded_year' => $this->founded_year ?: null,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'description' => $this->description ?: null,
                'mission' => $this->mission ?: null,
            ]);
        } else {
            $company = Company::create([
                'user_id' => auth()->id(),
                'name' => $this->name,
                'slug' => Str::slug($this->name).'-'.Str::lower(Str::random(5)),
                'logo' => $logoPath,
                'website' => $this->website ?: null,
                'location' => $this->location ?: null,
                'industry' => $this->industry ?: null,
                'size' => $this->size ?: null,
                'founded_year' => $this->founded_year ?: null,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'description' => $this->description ?: null,
                'mission' => $this->mission ?: null,
            ]);
        }

        ActivityLog::record(auth()->user(), 'company_updated', "Updated company profile for {$this->name}.");

        session()->flash('success', $isUpdate ? 'Company profile updated successfully.' : 'Company profile created successfully.');
    }

    public function render()
    {
        return view('livewire.company.company-settings', [
            'company' => auth()->user()->company,
        ]);
    }
}
