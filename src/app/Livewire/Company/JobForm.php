<?php

namespace App\Livewire\Company;

use App\Models\ActivityLog;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Skill;
use App\Notifications\NewJobPosted;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Post a Job')]
class JobForm extends Component
{
    public ?Job $job = null;

    public string $title = '';

    public ?int $category_id = null;

    public string $description = '';

    public string $requirements = '';

    public string $responsibilities = '';

    public string $benefits = '';

    public ?int $salary_min = null;

    public ?int $salary_max = null;

    public string $salary_currency = 'USD';

    public bool $is_salary_negotiable = false;

    public string $location = '';

    public string $location_type = 'on_site';

    public string $job_type = 'full_time';

    public string $experience_level = 'entry';

    public int $vacancies = 1;

    public string $status = 'draft';

    public ?string $deadline = null;

    public bool $is_featured = false;

    public array $skills = [];

    public function mount(?Job $job = null): void
    {
        $company = auth()->user()->company;

        if ($job && $company && $job->company_id !== $company->id) {
            abort(403);
        }

        if ($job) {
            $this->job = $job;
            $this->title = $job->title;
            $this->category_id = $job->category_id;
            $this->description = $job->description ?? '';
            $this->requirements = $job->requirements ?? '';
            $this->responsibilities = $job->responsibilities ?? '';
            $this->benefits = $job->benefits ?? '';
            $this->salary_min = $job->salary_min ? (int) $job->salary_min : null;
            $this->salary_max = $job->salary_max ? (int) $job->salary_max : null;
            $this->salary_currency = $job->salary_currency ?? 'USD';
            $this->is_salary_negotiable = $job->is_salary_negotiable;
            $this->location = $job->location ?? '';
            $this->location_type = $job->location_type;
            $this->job_type = $job->job_type;
            $this->experience_level = $job->experience_level;
            $this->vacancies = $job->vacancies;
            $this->status = $job->status;
            $this->deadline = $job->deadline?->format('Y-m-d');
            $this->is_featured = $job->is_featured;
            $this->skills = $job->skills()->pluck('skills.id')->map(fn ($id) => (string) $id)->all();
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:job_categories,id'],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
            'salary_currency' => ['required', 'string', 'size:3'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_type' => ['required', 'in:on_site,remote,hybrid'],
            'job_type' => ['required', 'in:full_time,part_time,contract,internship,freelance'],
            'experience_level' => ['required', 'in:entry,junior,mid,senior,lead,executive'],
            'vacancies' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:draft,published'],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
            'skills' => ['array'],
            'skills.*' => ['exists:skills,id'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $company = auth()->user()->company;

        if (! $company) {
            session()->flash('error', 'Please set up your company profile before posting jobs.');

            return;
        }

        $data = [
            'company_id' => $company->id,
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements ?: null,
            'responsibilities' => $this->responsibilities ?: null,
            'benefits' => $this->benefits ?: null,
            'salary_min' => $this->is_salary_negotiable ? null : $this->salary_min,
            'salary_max' => $this->is_salary_negotiable ? null : $this->salary_max,
            'salary_currency' => $this->salary_currency,
            'is_salary_negotiable' => $this->is_salary_negotiable,
            'location' => $this->location ?: null,
            'location_type' => $this->location_type,
            'job_type' => $this->job_type,
            'experience_level' => $this->experience_level,
            'vacancies' => $this->vacancies,
            'status' => $this->status,
            'deadline' => $this->deadline,
            'is_featured' => $this->is_featured,
        ];

        if ($this->job) {
            $this->job->update($data);
            $this->job->skills()->sync($this->skills);
            ActivityLog::record(auth()->user(), 'job_updated', "Updated job posting {$this->title}.");

            session()->flash('success', 'Job updated successfully.');
        } else {
            $job = Job::create($data + ['slug' => Str::slug($this->title).'-'.Str::lower(Str::random(5))]);
            $job->skills()->attach($this->skills);
            ActivityLog::record(auth()->user(), 'job_created', "Created job posting {$this->title}.");

            if ($this->status === Job::STATUS_PUBLISHED) {
                $company->followers()->each(fn ($follower) => $follower->notify(new NewJobPosted($job)));
            }

            session()->flash('success', 'Job published successfully.');

            $this->redirect(route('company.jobs'));
        }
    }

    public function render()
    {
        return view('livewire.company.job-form', [
            'categories' => JobCategory::where('is_active', true)->orderBy('name')->get(),
            'allSkills' => Skill::orderBy('name')->get(),
            'jobTypes' => Job::TYPES,
            'locationTypes' => Job::LOCATION_TYPES,
            'experienceLevels' => Job::EXPERIENCE_LEVELS,
        ]);
    }
}
