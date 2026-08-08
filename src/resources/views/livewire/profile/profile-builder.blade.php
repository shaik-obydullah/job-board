<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-8 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Builder</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Complete your profile to stand out to employers.</p>
            </div>
            <a href="{{ route('profile.public', auth()->user()->username) }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">View public profile</a>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-4">
            {{-- Sidebar --}}
            <aside class="lg:col-span-1">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ auth()->user()->photo_url }}" class="h-12 w-12 rounded-full object-cover" alt="">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $profileCompletion }}% complete</p>
                        </div>
                    </div>
                    <div class="mb-4 h-1.5 rounded-full bg-gray-100 dark:bg-gray-800">
                        <div class="h-1.5 rounded-full bg-primary-600 transition-all" style="width: {{ $profileCompletion }}%"></div>
                    </div>
                    <nav class="space-y-1">
                        @foreach ($sectionList as $key => $item)
                            <a href="{{ route('profile.section', $key) }}" wire:navigate
                               class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition
                               {{ $section === $key ? 'bg-primary-50 font-medium text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- Content --}}
            <div class="lg:col-span-3">
                @if ($section === 'basic-info')
                    <form wire:submit="saveBasicInfo" class="space-y-6">
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                            <div class="mb-6 flex items-center gap-4">
                                <img src="{{ auth()->user()->photo_url }}" class="h-16 w-16 rounded-full object-cover" alt="">
                                <input type="file" wire:model="photo" accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 dark:text-gray-400 dark:file:bg-primary-900/40 dark:file:text-primary-300">
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <x-input label="Full name" name="name" wire:model="name" required />
                                <x-input label="Headline" name="headline" wire:model="headline" placeholder="Senior Laravel Developer" />
                                <x-input label="Phone" name="phone" wire:model="phone" />
                                <x-input label="Location" name="location" wire:model="location" placeholder="City, Country" />
                                <x-input label="Date of birth" name="date_of_birth" wire:model="date_of_birth" type="date" />
                                <x-select label="Gender" name="gender" wire:model="gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" />
                                <div class="sm:col-span-2">
                                    <x-input label="Address" name="address" wire:model="address" />
                                </div>
                                <x-input label="Website" name="website" wire:model="website" placeholder="https://..." />
                                <x-input label="LinkedIn" name="linkedin" wire:model="linkedin" placeholder="https://linkedin.com/in/..." />
                                <x-input label="GitHub" name="github" wire:model="github" placeholder="https://github.com/..." />
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Professional Summary</h2>
                            <x-textarea label="Summary" name="summary" wire:model="summary" rows="5" placeholder="Write a short summary about yourself..." />
                            <x-select label="Profile visibility" name="visibility" wire:model="visibility" :options="['public' => 'Public', 'private' => 'Private']" />
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save changes</button>
                        </div>
                    </form>

                @elseif ($section === 'summary')
                    <form wire:submit="saveBasicInfo" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Professional Summary</h2>
                        <x-textarea label="Summary" name="summary" wire:model="summary" rows="8" placeholder="Describe your experience, strengths, and career goals..." />
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save summary</button>
                        </div>
                    </form>

                @elseif ($section === 'education')
                    <form wire:submit="saveEducations" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Education</h2>
                            <button type="button" wire:click="addEducation" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add education</button>
                        </div>
                        @forelse ($educations as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2"><x-input label="School" name="educations.{{ $index }}.school" wire:model="educations.{{ $index }}.school" /></div>
                                    <x-input label="Degree" name="educations.{{ $index }}.degree" wire:model="educations.{{ $index }}.degree" />
                                    <x-input label="Field of study" name="educations.{{ $index }}.field_of_study" wire:model="educations.{{ $index }}.field_of_study" />
                                    <x-input label="Start date" name="educations.{{ $index }}.start_date" wire:model="educations.{{ $index }}.start_date" type="month" />
                                    <div>
                                        <x-input label="End date" name="educations.{{ $index }}.end_date" wire:model="educations.{{ $index }}.end_date" type="month" :disabled="$item['is_current']" />
                                        <label class="mt-2 flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" wire:model="educations.{{ $index }}.is_current" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            I currently attend
                                        </label>
                                    </div>
                                    <div class="sm:col-span-2"><x-textarea label="Description" name="educations.{{ $index }}.description" wire:model="educations.{{ $index }}.description" rows="2" /></div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No education added yet.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save education</button>
                        </div>
                    </form>

                @elseif ($section === 'employment')
                    <form wire:submit="saveEmployments" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Work Experience</h2>
                            <button type="button" wire:click="addEmployment" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add experience</button>
                        </div>
                        @forelse ($employments as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <x-input label="Job title" name="employments.{{ $index }}.job_title" wire:model="employments.{{ $index }}.job_title" />
                                    <x-input label="Company" name="employments.{{ $index }}.company_name" wire:model="employments.{{ $index }}.company_name" />
                                    <div class="sm:col-span-2"><x-input label="Location" name="employments.{{ $index }}.location" wire:model="employments.{{ $index }}.location" /></div>
                                    <x-input label="Start date" name="employments.{{ $index }}.start_date" wire:model="employments.{{ $index }}.start_date" type="month" />
                                    <div>
                                        <x-input label="End date" name="employments.{{ $index }}.end_date" wire:model="employments.{{ $index }}.end_date" type="month" :disabled="$item['is_current']" />
                                        <label class="mt-2 flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" wire:model="employments.{{ $index }}.is_current" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            I currently work here
                                        </label>
                                    </div>
                                    <div class="sm:col-span-2"><x-textarea label="Description" name="employments.{{ $index }}.description" wire:model="employments.{{ $index }}.description" rows="3" /></div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No work experience added yet.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save experience</button>
                        </div>
                    </form>

                @elseif ($section === 'certifications')
                    <form wire:submit="saveCertifications" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Certifications</h2>
                            <button type="button" wire:click="addCertification" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add certification</button>
                        </div>
                        @forelse ($certifications as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <x-input label="Name" name="certifications.{{ $index }}.name" wire:model="certifications.{{ $index }}.name" />
                                    <x-input label="Issuing organization" name="certifications.{{ $index }}.issuing_organization" wire:model="certifications.{{ $index }}.issuing_organization" />
                                    <x-input label="Issue date" name="certifications.{{ $index }}.issue_date" wire:model="certifications.{{ $index }}.issue_date" type="date" />
                                    <x-input label="Expiration date" name="certifications.{{ $index }}.expiration_date" wire:model="certifications.{{ $index }}.expiration_date" type="date" />
                                    <x-input label="Credential ID" name="certifications.{{ $index }}.credential_id" wire:model="certifications.{{ $index }}.credential_id" />
                                    <x-input label="Credential URL" name="certifications.{{ $index }}.credential_url" wire:model="certifications.{{ $index }}.credential_url" />
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No certifications added yet.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save certifications</button>
                        </div>
                    </form>

                @elseif ($section === 'skills')
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Skills</h2>
                        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end">
                            <div class="flex-1">
                                <x-input label="Add a skill" name="newSkillName" wire:model="newSkillName" placeholder="e.g. Laravel" />
                            </div>
                            <div class="sm:w-48">
                                <x-select label="Proficiency" name="newSkillProficiency" wire:model="newSkillProficiency" :options="$this->proficiencyOptions" />
                            </div>
                            <button wire:click="addSkill" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">Add</button>
                        </div>
                        @error('newSkillName') <p class="mb-3 text-xs text-red-600">{{ $message }}</p> @enderror

                        @if ($selectedSkills)
                            <div class="mb-4 flex flex-wrap gap-2">
                                @foreach ($selectedSkills as $skill)
                                    <span class="inline-flex items-center gap-2 rounded-full bg-primary-50 px-3 py-1.5 text-sm text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                                        {{ $skill['name'] }}
                                        <select wire:change="setSkillProficiency({{ $skill['id'] }}, $event.target.value)" class="rounded bg-transparent text-xs font-normal focus:outline-none">
                                            @foreach ($this->proficiencyOptions as $value => $label)
                                                <option value="{{ $value }}" @selected($skill['proficiency'] === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" wire:click="removeSkill({{ $skill['id'] }})" class="text-primary-400 hover:text-primary-600">&times;</button>
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Add skills to highlight your strengths. You can also pick from the suggestions below.</p>
                        @endif

                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach ($this->allSkills->take(30) as $skill)
                                <button type="button" wire:click="addSkillPreset({{ $skill->id }})"
                                        class="rounded-full border border-gray-300 px-3 py-1 text-xs text-gray-600 hover:border-primary-400 hover:text-primary-600 dark:border-gray-700 dark:text-gray-300">
                                    {{ $skill->name }}
                                </button>
                            @endforeach
                        </div>

                        <div class="flex justify-end">
                            <button wire:click="saveSkills" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save skills</button>
                        </div>
                    </div>

                @elseif ($section === 'languages')
                    <form wire:submit="saveLanguages" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Languages</h2>
                            <button type="button" wire:click="addLanguage" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add language</button>
                        </div>
                        @forelse ($languages as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <x-input label="Language" name="languages.{{ $index }}.name" wire:model="languages.{{ $index }}.name" />
                                    <x-select label="Proficiency" name="languages.{{ $index }}.proficiency" wire:model="languages.{{ $index }}.proficiency" :options="\App\Models\Language::PROFICIENCIES" />
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No languages added yet.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save languages</button>
                        </div>
                    </form>

                @elseif ($section === 'portfolio')
                    <form wire:submit="savePortfolios" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Portfolio</h2>
                            <button type="button" wire:click="addPortfolio" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add project</button>
                        </div>
                        @forelse ($portfolios as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <x-input label="Title" name="portfolios.{{ $index }}.title" wire:model="portfolios.{{ $index }}.title" />
                                    <x-input label="URL" name="portfolios.{{ $index }}.url" wire:model="portfolios.{{ $index }}.url" placeholder="https://..." />
                                    <div class="sm:col-span-2"><x-textarea label="Description" name="portfolios.{{ $index }}.description" wire:model="portfolios.{{ $index }}.description" rows="2" /></div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Showcase your work and projects.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save portfolio</button>
                        </div>
                    </form>

                @elseif ($section === 'memberships')
                    <form wire:submit="saveMemberships" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Memberships</h2>
                            <button type="button" wire:click="addMembership" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add membership</button>
                        </div>
                        @forelse ($memberships as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-3">
                                    <x-input label="Organization" name="memberships.{{ $index }}.organization" wire:model="memberships.{{ $index }}.organization" />
                                    <x-input label="Role" name="memberships.{{ $index }}.role" wire:model="memberships.{{ $index }}.role" />
                                    <x-input label="Year" name="memberships.{{ $index }}.year" wire:model="memberships.{{ $index }}.year" />
                                    <div class="sm:col-span-3"><x-textarea label="Description" name="memberships.{{ $index }}.description" wire:model="memberships.{{ $index }}.description" rows="2" /></div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No memberships added yet.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save memberships</button>
                        </div>
                    </form>

                @elseif ($section === 'awards')
                    <form wire:submit="saveAwards" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Awards & Honors</h2>
                            <button type="button" wire:click="addAward" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add award</button>
                        </div>
                        @forelse ($awards as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-3">
                                    <x-input label="Title" name="awards.{{ $index }}.title" wire:model="awards.{{ $index }}.title" />
                                    <x-input label="Issuer" name="awards.{{ $index }}.issuer" wire:model="awards.{{ $index }}.issuer" />
                                    <x-input label="Date" name="awards.{{ $index }}.date" wire:model="awards.{{ $index }}.date" type="date" />
                                    <div class="sm:col-span-3"><x-textarea label="Description" name="awards.{{ $index }}.description" wire:model="awards.{{ $index }}.description" rows="2" /></div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No awards added yet.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save awards</button>
                        </div>
                    </form>

                @elseif ($section === 'hobbies')
                    <form wire:submit="saveHobbies" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Hobbies & Interests</h2>
                            <button type="button" wire:click="addHobby" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add hobby</button>
                        </div>
                        @forelse ($hobbies as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <x-input label="Hobby" name="hobbies.{{ $index }}.name" wire:model="hobbies.{{ $index }}.name" />
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Share your interests outside of work.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save hobbies</button>
                        </div>
                    </form>

                @elseif ($section === 'references')
                    <form wire:submit="saveReferences" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">References</h2>
                            <button type="button" wire:click="addReference" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">+ Add reference</button>
                        </div>
                        @forelse ($references as $index => $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <x-input label="Name" name="references.{{ $index }}.name" wire:model="references.{{ $index }}.name" />
                                    <x-input label="Relationship" name="references.{{ $index }}.relationship" wire:model="references.{{ $index }}.relationship" />
                                    <x-input label="Job title" name="references.{{ $index }}.job_title" wire:model="references.{{ $index }}.job_title" />
                                    <x-input label="Company" name="references.{{ $index }}.company_name" wire:model="references.{{ $index }}.company_name" />
                                    <x-input label="Email" name="references.{{ $index }}.email" wire:model="references.{{ $index }}.email" type="email" />
                                    <x-input label="Phone" name="references.{{ $index }}.phone" wire:model="references.{{ $index }}.phone" />
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No references added yet.</p>
                            </div>
                        @endforelse
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save references</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
