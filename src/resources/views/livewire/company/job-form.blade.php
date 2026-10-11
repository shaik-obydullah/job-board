<div>
    <x-alert type="success">{{ session('success') }}</x-alert>
    <x-alert type="error">{{ session('error') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $job ? 'Edit Job' : 'Post a Job' }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Fill in the details below. Save as draft or publish immediately.</p>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <form wire:submit="save" class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Job details</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-input label="Job title" name="title" wire:model="title" required placeholder="Senior Laravel Developer" />
                    </div>
                    <div>
                        <x-select label="Category" name="category_id" wire:model="category_id" :options="$categories->pluck('name', 'id')->all()" placeholder="Select a category" />
                    </div>
                    <div>
                        <x-input label="Location" name="location" wire:model="location" placeholder="Remote / New York, NY" />
                    </div>
                    <div>
                        <x-select label="Location type" name="location_type" wire:model="location_type" :options="$locationTypes" />
                    </div>
                    <div>
                        <x-select label="Job type" name="job_type" wire:model="job_type" :options="$jobTypes" />
                    </div>
                    <div>
                        <x-select label="Experience level" name="experience_level" wire:model="experience_level" :options="$experienceLevels" />
                    </div>
                    <div>
                        <x-input label="Vacancies" name="vacancies" wire:model="vacancies" type="number" min="1" />
                    </div>
                    <div>
                        <x-input label="Application deadline" name="deadline" wire:model="deadline" type="date" />
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Compensation</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <x-input label="Minimum salary" name="salary_min" wire:model="salary_min" type="number" min="0" placeholder="50000" />
                    </div>
                    <div>
                        <x-input label="Maximum salary" name="salary_max" wire:model="salary_max" type="number" min="0" placeholder="90000" />
                    </div>
                    <div>
                        <x-input label="Currency" name="salary_currency" wire:model="salary_currency" maxlength="3" placeholder="USD" />
                    </div>
                </div>
                <label class="mt-3 flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" wire:model="is_salary_negotiable" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    Salary negotiable
                </label>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Description</h2>
                <x-textarea label="Job description" name="description" wire:model="description" rows="6" required placeholder="Describe the role and responsibilities..." />
                <x-textarea label="Requirements" name="requirements" wire:model="requirements" rows="4" placeholder="List required skills and qualifications..." />
                <x-textarea label="Responsibilities" name="responsibilities" wire:model="responsibilities" rows="4" placeholder="What will the candidate do?" />
                <x-textarea label="Benefits" name="benefits" wire:model="benefits" rows="3" placeholder="What does your company offer?" />
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Skills</h2>
                <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">Select the skills required for this role.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($allSkills as $skill)
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border px-3 py-1.5 text-sm transition
                            {{ in_array((string) $skill->id, $skills, true) ? 'border-primary-600 bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'border-gray-300 text-gray-600 hover:border-primary-400 dark:border-gray-700 dark:text-gray-300' }}">
                            <input type="checkbox" wire:model="skills" value="{{ $skill->id }}" class="hidden">
                            {{ $skill->name }}
                        </label>
                    @endforeach
                </div>
                @error('skills') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Publish</h2>
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 transition has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 dark:border-gray-700 dark:has-[:checked]:bg-primary-900/40">
                        <input type="radio" wire:model="status" value="draft" class="mt-1 h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span>
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Save as draft</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Keep it hidden until you're ready.</span>
                        </span>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 transition has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 dark:border-gray-700 dark:has-[:checked]:bg-primary-900/40">
                        <input type="radio" wire:model="status" value="published" class="mt-1 h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span>
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Publish now</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Make it visible to job seekers immediately.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('company.jobs') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    {{ $job ? 'Update job' : 'Save job' }}
                </button>
            </div>
        </form>
    </div>
</div>
