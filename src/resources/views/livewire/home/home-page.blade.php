<div>
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-primary-50 via-white to-primary-100/50 dark:from-gray-900 dark:via-gray-950 dark:to-primary-950/30">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl dark:text-white">
                    Find your next
                    <span class="bg-gradient-to-r from-primary-600 to-primary-400 bg-clip-text text-transparent">career move</span>
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                    Discover thousands of job opportunities from top companies. Build your profile, apply in one click.
                </p>
                <form wire:submit="search" class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <div class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                        <label for="search" class="sr-only">Search jobs</label>
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input type="text" wire:model="search" id="search" placeholder="Job title, skill, or keyword"
                                   class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
                        </div>
                    </div>
                    <div class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <input type="text" wire:model="location" placeholder="City or location"
                                   class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
                        </div>
                    </div>
                    <button type="submit" class="rounded-xl bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="border-y border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto grid max-w-7xl grid-cols-3 divide-x divide-gray-200 px-4 py-8 sm:px-6 lg:px-8 dark:divide-gray-800">
            <div class="text-center">
                <p class="text-2xl font-bold text-primary-600 sm:text-3xl dark:text-primary-400">{{ number_format($stats['jobs']) }}</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Open Jobs</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-primary-600 sm:text-3xl dark:text-primary-400">{{ number_format($stats['companies']) }}</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Companies</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-primary-600 sm:text-3xl dark:text-primary-400">{{ number_format($stats['users']) }}</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Job Seekers</p>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Browse by category</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Explore jobs in your field of interest</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="hidden text-sm font-semibold text-primary-600 hover:text-primary-500 sm:block">View all jobs →</a>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('jobs.index', ['category' => $category->slug]) }}" class="group flex items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 transition hover:border-primary-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-primary-700">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 group-hover:bg-primary-100 dark:bg-primary-900/40 dark:text-primary-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-gray-900 group-hover:text-primary-600 dark:text-white">{{ $category->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $category->jobs_count }} jobs</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured jobs --}}
    @if ($featuredJobs->isNotEmpty())
        <section class="border-y border-gray-200 bg-white py-16 dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-end justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Featured jobs</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hand-picked opportunities from our top employers</p>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-500">View all</a>
                </div>
                <div class="grid gap-5 lg:grid-cols-2">
                    @foreach ($featuredJobs as $job)
                        <x-job-card :job="$job" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Recent jobs --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Recent jobs</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Fresh opportunities posted by companies</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-500">View all →</a>
        </div>
        <div class="grid gap-5 lg:grid-cols-2">
            @foreach ($recentJobs as $job)
                <x-job-card :job="$job" />
            @endforeach
        </div>
    </section>

    {{-- Top companies --}}
    <section class="border-t border-gray-200 bg-white py-16 dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Top companies</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Trusted employers hiring right now</p>
                </div>
                <a href="{{ route('companies.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-500">All companies →</a>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ($companies as $company)
                    <a href="{{ route('companies.show', $company) }}" class="group rounded-2xl border border-gray-200 bg-gray-50 p-6 text-center transition hover:border-primary-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-800/50">
                        @if ($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" class="mx-auto h-14 w-14 rounded-2xl object-cover" alt="">
                        @else
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-100 text-xl font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                        @endif
                        <p class="mt-3 truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $company->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $company->jobs_count }} open jobs</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-r from-primary-600 to-primary-500 px-6 py-12 text-center sm:px-12">
            <h2 class="text-3xl font-bold text-white">Ready to take the next step?</h2>
            <p class="mx-auto mt-3 max-w-xl text-primary-100">
                @auth
                    Explore new opportunities tailored to your skills today.
                @else
                    Join thousands of professionals and find your dream job today.
                @endauth
            </p>
            <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                @auth
                    <a href="{{ route('jobs.index') }}" class="rounded-xl bg-white px-8 py-3 text-sm font-semibold text-primary-700 hover:bg-primary-50">Browse jobs</a>
                @else
                    <a href="{{ route('register') }}" class="rounded-xl bg-white px-8 py-3 text-sm font-semibold text-primary-700 hover:bg-primary-50">Get started free</a>
                    <a href="{{ route('jobs.index') }}" class="rounded-xl bg-primary-700 px-8 py-3 text-sm font-semibold text-white hover:bg-primary-800">Explore jobs</a>
                @endauth
            </div>
        </div>
    </section>
</div>
