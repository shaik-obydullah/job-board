<?php

namespace App\Livewire\Profile;

use App\Models\Award;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Employment;
use App\Models\Hobby;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Portfolio;
use App\Models\Skill;
use App\Models\UserReference;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('My Profile')]
class ProfileBuilder extends Component
{
    use WithFileUploads;

    public string $section = 'basic-info';

    public $photo;

    public string $name = '';

    public string $headline = '';

    public string $phone = '';

    public string $location = '';

    public string $date_of_birth = '';

    public string $gender = '';

    public string $address = '';

    public string $website = '';

    public string $linkedin = '';

    public string $github = '';

    public string $summary = '';

    public string $visibility = 'public';

    public array $educations = [];

    public array $employments = [];

    public array $certifications = [];

    public array $languages = [];

    public array $portfolios = [];

    public array $memberships = [];

    public array $awards = [];

    public array $hobbies = [];

    public array $references = [];

    public array $selectedSkills = [];

    public string $newSkillName = '';

    public string $newSkillProficiency = 'intermediate';

    public function mount(?string $section = null): void
    {
        $this->section = $section ?? $this->section;

        $this->loadBasicInfo();
        $this->loadSection();
    }

    private function loadBasicInfo(): void
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->headline = $user->headline ?? '';
        $this->phone = $user->phone ?? '';
        $this->location = $user->location ?? '';
        $this->date_of_birth = $user->date_of_birth?->format('Y-m-d') ?? '';
        $this->gender = $user->gender ?? '';
        $this->address = $user->address ?? '';
        $this->website = $user->website ?? '';
        $this->linkedin = $user->linkedin ?? '';
        $this->github = $user->github ?? '';
        $this->summary = $user->summary ?? '';
        $this->visibility = $user->visibility ?? 'public';
    }

    private function loadSection(): void
    {
        $user = auth()->user();

        $this->educations = $user->educations->map(function ($item) {
            return [
                'id' => $item->id,
                'school' => $item->school,
                'degree' => $item->degree ?? '',
                'field_of_study' => $item->field_of_study ?? '',
                'start_date' => $item->start_date?->format('Y-m-d') ?? '',
                'end_date' => $item->end_date?->format('Y-m-d') ?? '',
                'is_current' => (bool) $item->is_current,
                'description' => $item->description ?? '',
            ];
        })->values()->toArray();

        $this->employments = $user->employments->map(function ($item) {
            return [
                'id' => $item->id,
                'job_title' => $item->job_title,
                'company_name' => $item->company_name,
                'location' => $item->location ?? '',
                'start_date' => $item->start_date?->format('Y-m-d') ?? '',
                'end_date' => $item->end_date?->format('Y-m-d') ?? '',
                'is_current' => (bool) $item->is_current,
                'description' => $item->description ?? '',
            ];
        })->values()->toArray();

        $this->certifications = $user->certifications->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'issuing_organization' => $item->issuing_organization ?? '',
                'issue_date' => $item->issue_date?->format('Y-m-d') ?? '',
                'expiration_date' => $item->expiration_date?->format('Y-m-d') ?? '',
                'credential_id' => $item->credential_id ?? '',
                'credential_url' => $item->credential_url ?? '',
            ];
        })->values()->toArray();

        $this->languages = $user->languages->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'proficiency' => $item->proficiency,
        ])->values()->toArray();

        $this->portfolios = $user->portfolios->map(fn ($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description ?? '',
            'url' => $item->url ?? '',
        ])->values()->toArray();

        $this->memberships = $user->memberships->map(fn ($item) => [
            'id' => $item->id,
            'organization' => $item->organization,
            'role' => $item->role ?? '',
            'year' => $item->year ?? '',
            'description' => $item->description ?? '',
        ])->values()->toArray();

        $this->awards = $user->awards->map(fn ($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'issuer' => $item->issuer ?? '',
            'date' => $item->date?->format('Y-m-d') ?? '',
            'description' => $item->description ?? '',
        ])->values()->toArray();

        $this->hobbies = $user->hobbies->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
        ])->values()->toArray();

        $this->references = $user->references->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'job_title' => $item->job_title ?? '',
            'company_name' => $item->company_name ?? '',
            'email' => $item->email ?? '',
            'phone' => $item->phone ?? '',
            'relationship' => $item->relationship ?? '',
        ])->values()->toArray();

        $this->selectedSkills = $user->skills->map(fn ($skill) => [
            'id' => $skill->id,
            'name' => $skill->name,
            'proficiency' => $skill->pivot->proficiency,
        ])->values()->toArray();
    }

    #[Computed]
    public function allSkills()
    {
        return Skill::orderBy('name')->get();
    }

    #[Computed]
    public function proficiencyOptions(): array
    {
        return [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Expert',
        ];
    }

    public function setSection(string $section): void
    {
        $this->section = $section;
        $this->loadSection();
        $this->redirect(route('profile.section', $section));
    }

    public function saveBasicInfo(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'summary' => ['nullable', 'string'],
            'visibility' => ['required', 'in:public,private'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = auth()->user();

        $photoPath = $user->photo;

        if ($this->photo) {
            $photoPath = $this->photo->store('avatars', 'public');
        }

        $user->update([
            'name' => $this->name,
            'photo' => $photoPath,
            'headline' => $this->headline ?: null,
            'phone' => $this->phone ?: null,
            'location' => $this->location ?: null,
            'date_of_birth' => $this->date_of_birth ?: null,
            'gender' => $this->gender ?: null,
            'address' => $this->address ?: null,
            'website' => $this->website ?: null,
            'linkedin' => $this->linkedin ?: null,
            'github' => $this->github ?: null,
            'summary' => $this->summary ?: null,
            'visibility' => $this->visibility,
        ]);

        $this->loadBasicInfo();

        session()->flash('success', 'Basic information saved.');
    }

    public function addEducation(): void
    {
        $this->educations[] = [
            'id' => null,
            'school' => '',
            'degree' => '',
            'field_of_study' => '',
            'start_date' => '',
            'end_date' => '',
            'is_current' => false,
            'description' => '',
        ];
    }

    public function saveEducations(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->educations as $index => $item) {
            if ($item['is_current']) {
                $this->educations[$index]['end_date'] = '';
            }

            $data = [
                'school' => $item['school'],
                'degree' => $item['degree'] ?: null,
                'field_of_study' => $item['field_of_study'] ?: null,
                'start_date' => $item['start_date'] ?: null,
                'end_date' => $item['is_current'] ? null : ($item['end_date'] ?: null),
                'is_current' => (bool) $item['is_current'],
                'description' => $item['description'] ?: null,
            ];

            if ($item['id']) {
                $education = Education::where('user_id', $user->id)->findOrFail($item['id']);
                $education->update($data);
                $ids[] = $education->id;
            } else {
                $education = $user->educations()->create($data);
                $ids[] = $education->id;
            }
        }

        Education::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Education saved.');
    }

    public function addEmployment(): void
    {
        $this->employments[] = [
            'id' => null,
            'job_title' => '',
            'company_name' => '',
            'location' => '',
            'start_date' => '',
            'end_date' => '',
            'is_current' => false,
            'description' => '',
        ];
    }

    public function saveEmployments(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->employments as $index => $item) {
            if ($item['is_current']) {
                $this->employments[$index]['end_date'] = '';
            }

            $data = [
                'job_title' => $item['job_title'],
                'company_name' => $item['company_name'],
                'location' => $item['location'] ?: null,
                'start_date' => $item['start_date'] ?: null,
                'end_date' => $item['is_current'] ? null : ($item['end_date'] ?: null),
                'is_current' => (bool) $item['is_current'],
                'description' => $item['description'] ?: null,
            ];

            if ($item['id']) {
                $employment = Employment::where('user_id', $user->id)->findOrFail($item['id']);
                $employment->update($data);
                $ids[] = $employment->id;
            } else {
                $employment = $user->employments()->create($data);
                $ids[] = $employment->id;
            }
        }

        Employment::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Work experience saved.');
    }

    public function addCertification(): void
    {
        $this->certifications[] = [
            'id' => null,
            'name' => '',
            'issuing_organization' => '',
            'issue_date' => '',
            'expiration_date' => '',
            'credential_id' => '',
            'credential_url' => '',
        ];
    }

    public function saveCertifications(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->certifications as $item) {
            $data = [
                'name' => $item['name'],
                'issuing_organization' => $item['issuing_organization'] ?: null,
                'issue_date' => $item['issue_date'] ?: null,
                'expiration_date' => $item['expiration_date'] ?: null,
                'credential_id' => $item['credential_id'] ?: null,
                'credential_url' => $item['credential_url'] ?: null,
            ];

            if ($item['id']) {
                $certification = Certification::where('user_id', $user->id)->findOrFail($item['id']);
                $certification->update($data);
                $ids[] = $certification->id;
            } else {
                $certification = $user->certifications()->create($data);
                $ids[] = $certification->id;
            }
        }

        Certification::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Certifications saved.');
    }

    public function addLanguage(): void
    {
        $this->languages[] = ['id' => null, 'name' => '', 'proficiency' => 'professional_working'];
    }

    public function saveLanguages(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->languages as $item) {
            $data = ['name' => $item['name'], 'proficiency' => $item['proficiency']];

            if ($item['id']) {
                $language = Language::where('user_id', $user->id)->findOrFail($item['id']);
                $language->update($data);
                $ids[] = $language->id;
            } else {
                $language = $user->languages()->create($data);
                $ids[] = $language->id;
            }
        }

        Language::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Languages saved.');
    }

    public function addPortfolio(): void
    {
        $this->portfolios[] = ['id' => null, 'title' => '', 'description' => '', 'url' => ''];
    }

    public function savePortfolios(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->portfolios as $item) {
            $data = [
                'title' => $item['title'],
                'description' => $item['description'] ?: null,
                'url' => $item['url'] ?: null,
            ];

            if ($item['id']) {
                $portfolio = Portfolio::where('user_id', $user->id)->findOrFail($item['id']);
                $portfolio->update($data);
                $ids[] = $portfolio->id;
            } else {
                $portfolio = $user->portfolios()->create($data);
                $ids[] = $portfolio->id;
            }
        }

        Portfolio::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Portfolio saved.');
    }

    public function addMembership(): void
    {
        $this->memberships[] = ['id' => null, 'organization' => '', 'role' => '', 'year' => '', 'description' => ''];
    }

    public function saveMemberships(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->memberships as $item) {
            $data = [
                'organization' => $item['organization'],
                'role' => $item['role'] ?: null,
                'year' => $item['year'] ?: null,
                'description' => $item['description'] ?: null,
            ];

            if ($item['id']) {
                $membership = Membership::where('user_id', $user->id)->findOrFail($item['id']);
                $membership->update($data);
                $ids[] = $membership->id;
            } else {
                $membership = $user->memberships()->create($data);
                $ids[] = $membership->id;
            }
        }

        Membership::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Memberships saved.');
    }

    public function addAward(): void
    {
        $this->awards[] = ['id' => null, 'title' => '', 'issuer' => '', 'date' => '', 'description' => ''];
    }

    public function saveAwards(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->awards as $item) {
            $data = [
                'title' => $item['title'],
                'issuer' => $item['issuer'] ?: null,
                'date' => $item['date'] ?: null,
                'description' => $item['description'] ?: null,
            ];

            if ($item['id']) {
                $award = Award::where('user_id', $user->id)->findOrFail($item['id']);
                $award->update($data);
                $ids[] = $award->id;
            } else {
                $award = $user->awards()->create($data);
                $ids[] = $award->id;
            }
        }

        Award::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Awards saved.');
    }

    public function addHobby(): void
    {
        $this->hobbies[] = ['id' => null, 'name' => ''];
    }

    public function saveHobbies(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->hobbies as $item) {
            if ($item['id']) {
                $hobby = Hobby::where('user_id', $user->id)->findOrFail($item['id']);
                $hobby->update(['name' => $item['name']]);
                $ids[] = $hobby->id;
            } else {
                $hobby = $user->hobbies()->create(['name' => $item['name']]);
                $ids[] = $hobby->id;
            }
        }

        Hobby::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'Hobbies saved.');
    }

    public function addReference(): void
    {
        $this->references[] = [
            'id' => null,
            'name' => '',
            'job_title' => '',
            'company_name' => '',
            'email' => '',
            'phone' => '',
            'relationship' => '',
        ];
    }

    public function saveReferences(): void
    {
        $user = auth()->user();
        $ids = [];

        foreach ($this->references as $item) {
            $data = [
                'name' => $item['name'],
                'job_title' => $item['job_title'] ?: null,
                'company_name' => $item['company_name'] ?: null,
                'email' => $item['email'] ?: null,
                'phone' => $item['phone'] ?: null,
                'relationship' => $item['relationship'] ?: null,
            ];

            if ($item['id']) {
                $reference = UserReference::where('user_id', $user->id)->findOrFail($item['id']);
                $reference->update($data);
                $ids[] = $reference->id;
            } else {
                $reference = $user->references()->create($data);
                $ids[] = $reference->id;
            }
        }

        UserReference::where('user_id', $user->id)->whereNotIn('id', $ids)->delete();

        $this->loadSection();
        session()->flash('success', 'References saved.');
    }

    public function addSkill(): void
    {
        $this->validate(['newSkillName' => ['required', 'string', 'max:255']]);

        $skill = Skill::firstOrCreate(
            ['slug' => Str::slug($this->newSkillName)],
            ['name' => $this->newSkillName]
        );

        if (! collect($this->selectedSkills)->contains('id', $skill->id)) {
            $this->selectedSkills[] = [
                'id' => $skill->id,
                'name' => $skill->name,
                'proficiency' => $this->newSkillProficiency,
            ];
        }

        $this->newSkillName = '';
        $this->newSkillProficiency = 'intermediate';
    }

    public function addSkillPreset(int $skillId): void
    {
        if (collect($this->selectedSkills)->contains('id', $skillId)) {
            return;
        }

        $skill = Skill::findOrFail($skillId);

        $this->selectedSkills[] = [
            'id' => $skill->id,
            'name' => $skill->name,
            'proficiency' => 'intermediate',
        ];
    }

    public function removeSkill(int $skillId): void
    {
        $this->selectedSkills = array_values(array_filter($this->selectedSkills, fn ($item) => $item['id'] !== $skillId));
    }

    public function setSkillProficiency(int $skillId, string $proficiency): void
    {
        foreach ($this->selectedSkills as &$item) {
            if ($item['id'] === $skillId) {
                $item['proficiency'] = $proficiency;
            }
        }
    }

    public function saveSkills(): void
    {
        $user = auth()->user();

        $sync = collect($this->selectedSkills)->mapWithKeys(fn ($item) => [
            $item['id'] => ['proficiency' => $item['proficiency']],
        ])->all();

        $user->skills()->sync($sync);

        $this->loadSection();
        session()->flash('success', 'Skills saved.');
    }

    public function render()
    {
        return view('livewire.profile.profile-builder', [
            'sectionList' => [
                'basic-info' => ['label' => 'Basic Information', 'icon' => 'user'],
                'summary' => ['label' => 'Professional Summary', 'icon' => 'document'],
                'education' => ['label' => 'Education', 'icon' => 'academic'],
                'employment' => ['label' => 'Work Experience', 'icon' => 'briefcase'],
                'certifications' => ['label' => 'Certifications', 'icon' => 'award'],
                'skills' => ['label' => 'Skills', 'icon' => 'lightning'],
                'languages' => ['label' => 'Languages', 'icon' => 'globe'],
                'portfolio' => ['label' => 'Portfolio', 'icon' => 'folder'],
                'memberships' => ['label' => 'Memberships', 'icon' => 'users'],
                'awards' => ['label' => 'Awards & Honors', 'icon' => 'star'],
                'hobbies' => ['label' => 'Hobbies & Interests', 'icon' => 'heart'],
                'references' => ['label' => 'References', 'icon' => 'phone'],
            ],
            'profileCompletion' => auth()->user()->profileCompletion(),
        ]);
    }
}
