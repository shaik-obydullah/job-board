<div>
    <x-breadcrumbs :items="[
        ['label' => 'Dashboard'],
    ]" />

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Here's your job search overview.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Applications</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $applicationCount }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Saved Jobs</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $savedCount }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Profile</p>
                        <p class="mt-1 text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $profileCompletion }}%</p>
                        <div class="mt-2 h-1.5 rounded-full bg-gray-100 dark:bg-gray-800">
                            <div class="h-1.5 rounded-full bg-primary-600 transition-all" style="width: {{ $profileCompletion }}%"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Your applications</h2>
                        <a href="{{ route('applied-jobs') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">View all</a>
                    </div>
                    @if ($recentApplications->isEmpty())
                        <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">You haven't applied to any jobs yet.</p>
                            <a href="{{ route('jobs.index') }}" class="mt-3 inline-block text-sm font-semibold text-primary-600 hover:text-primary-500">Browse jobs →</a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentApplications as $application)
                                <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                                            {{ strtoupper(substr($application->job->company->name, 0, 1)) }}
                                        </span>
                                        <div>
                                            <a href="{{ route('jobs.show', $application->job) }}" class="text-sm font-semibold text-gray-900 hover:text-primary-600 dark:text-white">{{ $application->job->title }}</a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $application->job->company->name }} · {{ $application->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <x-badge :color="\App\Support\JobMeta::applicationStatusColor($application->status)">
                                        {{ \App\Support\JobMeta::applicationStatuses()[$application->status] ?? $application->status }}
                                    </x-badge>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Profile strength</h2>
                    <div class="mt-4 flex items-center gap-4">
                        <div class="relative h-24 w-24">
                            <svg class="h-24 w-24 -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="currentColor" stroke-width="3" class="text-gray-200 dark:text-gray-700"></circle>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="{{ $profileCompletion }}, 100" class="text-primary-600"></circle>
                            </svg>
                            <span class="absolute inset-0 flex items-center justify-center text-lg font-bold text-gray-900 dark:text-white">{{ $profileCompletion }}%</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">A complete profile gets up to 3x more applications from employers.</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Complete profile</a>
                </div>

                @if ($recommendedJobs->isNotEmpty())
                    <div class="mt-6">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Recommended for you</h2>
                        <div class="space-y-3">
                            @foreach ($recommendedJobs as $job)
                                <a href="{{ route('jobs.show', $job) }}" class="block rounded-xl border border-gray-200 bg-white p-4 transition hover:border-primary-300 dark:border-gray-800 dark:bg-gray-900">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $job->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $job->company->name }} · {{ $job->location }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
