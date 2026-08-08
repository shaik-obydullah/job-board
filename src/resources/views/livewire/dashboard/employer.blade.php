<div>
    <x-breadcrumbs :items="[
        ['label' => 'Dashboard'],
    ]" />

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Employer Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if ($company)
                        {{ $company->name }}
                        @if ($company->is_verified) <x-badge color="green">Verified</x-badge> @endif
                    @else
                        Set up your company to start posting jobs.
                    @endif
                </p>
            </div>
            @if ($company)
                <a href="{{ route('company.jobs.create') }}" class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Post a job</a>
            @else
                <a href="{{ route('company.settings') }}" class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Set up company</a>
            @endif
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (! $company)
            <div class="rounded-2xl border border-dashed border-gray-300 p-12 text-center dark:border-gray-700">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-primary-600 dark:bg-primary-900/40 dark:text-primary-300">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </span>
                <h2 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Create your company profile</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500 dark:text-gray-400">
                    Add your company details so job seekers can learn about you before applying.
                </p>
                <a href="{{ route('company.settings') }}" class="mt-4 inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Get started</a>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active Jobs</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $jobCount }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Applications</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $applicationCount }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Views</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalViews) }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Followers</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $followerCount }}</p>
                </div>
            </div>

            <div class="mt-8">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent applications</h2>
                    <a href="{{ route('company.jobs') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">Manage jobs</a>
                </div>
                @if ($recentApplications->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No applications yet. Share your job listings to attract candidates.</p>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Candidate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Job</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Applied</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach ($recentApplications as $application)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('company.jobs.applicants', $application->job) }}" class="flex items-center gap-3">
                                                <img src="{{ $application->user->photo_url }}" class="h-9 w-9 rounded-full object-cover" alt="">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->user->name }}</span>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $application->job->title }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $application->created_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4">
                                            <x-badge :color="\App\Support\JobMeta::applicationStatusColor($application->status)">
                                                {{ \App\Support\JobMeta::applicationStatuses()[$application->status] ?? $application->status }}
                                            </x-badge>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
