<div>
    @php
        $types = \App\Support\JobMeta::jobTypes();
        $levels = \App\Support\JobMeta::experienceLevels();
    @endphp

    <x-alert type="success">{{ session('success') }}</x-alert>
    <x-alert type="error">{{ session('error') }}</x-alert>

    {{-- Breadcrumb --}}
    <x-breadcrumbs :items="[
        ['label' => 'Jobs', 'url' => route('jobs.index')],
        ['label' => $job->title],
    ]" />

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                {{-- Job header --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            @if ($job->company->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}" class="h-16 w-16 rounded-2xl object-cover" alt="">
                            @else
                                <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-100 text-2xl font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($job->company->name, 0, 1)) }}</span>
                            @endif
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $job->title }}</h1>
                                <a href="{{ route('companies.show', $job->company) }}" class="text-primary-600 hover:text-primary-500">{{ $job->company->name }}</a>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <x-badge color="primary">{{ $types[$job->job_type] ?? $job->job_type }}</x-badge>
                                    <x-badge color="gray">{{ $levels[$job->experience_level] ?? $job->experience_level }}</x-badge>
                                    <x-badge color="gray">{{ \App\Support\JobMeta::locationTypes()[$job->location_type] ?? $job->location_type }}</x-badge>
                                    @if ($job->is_featured) <x-badge color="amber">Featured</x-badge> @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $job->salary_label }}</span>
                            <span class="text-xs text-gray-400">{{ number_format($job->views_count) }} views</span>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 border-t border-gray-100 pt-6 sm:grid-cols-3 dark:border-gray-800">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Location</p>
                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $job->location }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Posted</p>
                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $job->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Deadline</p>
                            <p class="mt-1 text-sm font-medium {{ $job->hasExpired() ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                {{ $job->deadline?->format('M d, Y') ?? 'No deadline' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        @auth
                            @if (auth()->user()->isJobSeeker())
                                @if ($hasApplied)
                                    <span class="inline-flex items-center gap-2 rounded-lg bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700 dark:bg-green-950 dark:text-green-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Applied
                                    </span>
                                @else
                                    <button wire:click="openApplyModal" @if (! $job->isOpen()) disabled @endif
                                            class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-50">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                        {{ $job->isOpen() ? 'Apply Now' : 'Job Closed' }}
                                    </button>
                                @endif
                                <button wire:click="toggleSave"
                                        class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">
                                    <svg class="h-5 w-5 {{ $isSaved ? 'fill-red-500 text-red-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    {{ $isSaved ? 'Saved' : 'Save job' }}
                                </button>
                            @endif
                            <button wire:click="toggleFollow"
                                    class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">
                                {{ $isFollowing ? 'Following' : 'Follow company' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">Sign in to apply</a>
                        @endauth
                    </div>
                </div>

                {{-- Job description --}}
                <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Job Description</h2>
                    <div class="prose-content mt-3 text-sm text-gray-600 dark:text-gray-300">{!! nl2br(e($job->description)) !!}</div>
                </div>

                @if ($job->responsibilities)
                    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Responsibilities</h2>
                        <div class="prose-content mt-3 text-sm text-gray-600 dark:text-gray-300">{!! nl2br(e($job->responsibilities)) !!}</div>
                    </div>
                @endif

                @if ($job->requirements)
                    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Requirements</h2>
                        <div class="prose-content mt-3 text-sm text-gray-600 dark:text-gray-300">{!! nl2br(e($job->requirements)) !!}</div>
                    </div>
                @endif

                @if ($job->benefits)
                    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Benefits</h2>
                        <div class="prose-content mt-3 text-sm text-gray-600 dark:text-gray-300">{!! nl2br(e($job->benefits)) !!}</div>
                    </div>
                @endif

                {{-- Skills --}}
                @if ($job->skills->isNotEmpty())
                    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Skills required</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($job->skills as $skill)
                                <a href="{{ route('jobs.index', ['search' => $skill->name]) }}" class="rounded-full bg-primary-50 px-3 py-1 text-sm font-medium text-primary-700 hover:bg-primary-100 dark:bg-primary-900/40 dark:text-primary-300">{{ $skill->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center dark:border-gray-800 dark:bg-gray-900">
                    @if ($job->company->logo)
                        <img src="{{ asset('storage/' . $job->company->logo) }}" class="mx-auto h-20 w-20 rounded-2xl object-cover" alt="">
                    @else
                        <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-primary-100 text-3xl font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($job->company->name, 0, 1)) }}</span>
                    @endif
                    <a href="{{ route('companies.show', $job->company) }}" class="mt-3 block text-lg font-bold text-gray-900 hover:text-primary-600 dark:text-white">{{ $job->company->name }}</a>
                    @if ($job->company->is_verified) <x-badge color="green" class="mt-1">Verified</x-badge> @endif
                    <div class="mt-4 space-y-2 text-left text-sm text-gray-600 dark:text-gray-400">
                        @if ($job->company->location)
                            <p class="flex items-center gap-2"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> {{ $job->company->location }}</p>
                        @endif
                        @if ($job->company->website)
                            <p class="flex items-center gap-2"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" /><path stroke-linecap="round" stroke-linejoin="round" d="M10.172 13.828a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1" /></svg> <a href="{{ $job->company->website }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline">{{ $job->company->website }}</a></p>
                        @endif
                        @if ($job->company->size)
                            <p class="flex items-center gap-2"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg> {{ $job->company->size }} employees</p>
                        @endif
                        <p class="flex items-center gap-2"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg> {{ $job->company->publishedJobs()->count() }} open positions</p>
                    </div>
                </div>

                {{-- Related jobs --}}
                @if ($relatedJobs->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Similar jobs</h2>
                        <div class="space-y-4">
                            @foreach ($relatedJobs as $related)
                                <a href="{{ route('jobs.show', $related) }}" class="block rounded-xl border border-gray-100 p-3 transition hover:border-primary-300 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $related->title }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $related->company->name }} · {{ $related->location }}</p>
                                    <p class="mt-1 text-xs font-medium text-primary-600 dark:text-primary-400">{{ $related->salary_label }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Apply modal --}}
    @if ($showApplyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="$set('showApplyModal', false)"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                <div class="mb-4 flex items-start justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Apply for {{ $job->title }}</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $job->company->name }}</p>
                    </div>
                    <button wire:click="$set('showApplyModal', false)" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mb-4 rounded-lg bg-gray-50 p-4 text-sm text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    <p class="flex items-center gap-2"><svg class="h-4 w-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Your public profile will be shared with the employer.</p>
                </div>

                <form wire:submit="apply" class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Cover letter <span class="text-gray-400">(optional)</span></label>
                        <textarea wire:model="coverLetter" rows="6" placeholder="Introduce yourself and explain why you're a great fit..."
                                  class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                        @error('coverLetter') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                        <span wire:loading.remove>Submit application</span>
                        <span wire:loading>Submitting...</span>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
