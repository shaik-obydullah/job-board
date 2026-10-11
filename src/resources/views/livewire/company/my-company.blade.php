<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Company</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your company profile and job listings.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('company.settings') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">Edit profile</a>
                <a href="{{ route('company.jobs.create') }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Post a job</a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (! $company)
            <div class="rounded-2xl border border-dashed border-gray-300 p-16 text-center dark:border-gray-700">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-primary-600 dark:bg-primary-900/40 dark:text-primary-300">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </span>
                <h2 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No company profile yet</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your company profile to start posting jobs.</p>
                <a href="{{ route('company.settings') }}" class="mt-4 inline-block rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Set up company</a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-start gap-4">
                            @if ($company->logo)
                                <img src="{{ asset('storage/'.$company->logo) }}" class="h-16 w-16 rounded-2xl object-cover" alt="{{ $company->name }}">
                            @else
                                <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-100 text-2xl font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                            @endif
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $company->name }}
                                    @if ($company->is_verified) <x-badge color="green">Verified</x-badge> @endif
                                </h2>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $company->industry ?? 'General' }} &middot; {{ $company->location ?? 'Location not set' }}
                                </p>
                            </div>
                        </div>
                        @if ($company->description)
                            <p class="prose-content mt-4 text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $company->description }}</p>
                        @endif
                        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                            @if ($company->website)
                                <a href="{{ $company->website }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 010 5.656l-4 4a4 4 0 01-5.656-5.657l1.889-1.889M10.172 13.828a4 4 0 010-5.656l4-4a4 4 0 015.657 5.657l-1.889 1.889" /></svg>
                                    Website
                                </a>
                            @endif
                            @if ($company->email)
                                <span class="inline-flex items-center gap-1"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>{{ $company->email }}</span>
                            @endif
                            @if ($company->phone)
                                <span class="inline-flex items-center gap-1"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>{{ $company->phone }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center dark:border-gray-800 dark:bg-gray-900">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeJobs }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Active Jobs</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center dark:border-gray-800 dark:bg-gray-900">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $applicationCount }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Applications</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center dark:border-gray-800 dark:bg-gray-900">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $followerCount }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Followers</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center dark:border-gray-800 dark:bg-gray-900">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalViews) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Views</p>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Quick actions</h3>
                        </div>
                        <ul class="mt-3 space-y-2 text-sm">
                            <li><a href="{{ route('company.jobs') }}" class="flex items-center justify-between text-gray-600 hover:text-primary-600 dark:text-gray-300">My jobs <span>&rarr;</span></a></li>
                            <li><a href="{{ route('company.jobs.create') }}" class="flex items-center justify-between text-gray-600 hover:text-primary-600 dark:text-gray-300">Post a new job <span>&rarr;</span></a></li>
                            <li><a href="{{ route('companies.show', $company) }}" class="flex items-center justify-between text-gray-600 hover:text-primary-600 dark:text-gray-300">View public page <span>&rarr;</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
