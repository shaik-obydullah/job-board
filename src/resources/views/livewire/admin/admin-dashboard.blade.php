<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Overview of the platform.</p>
        </div>
        <span class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ now()->format('F j, Y') }}</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total users</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['users']) }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $stats['job_seekers'] }} seekers &middot; {{ $stats['employers'] }} employers</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Companies</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['companies']) }}</p>
            <p class="mt-1 text-xs text-gray-400">Registered employers</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Jobs</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['jobs']) }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $stats['open_jobs'] }} open now</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Applications</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['applications']) }}</p>
            <p class="mt-1 text-xs text-gray-400">Across all jobs</p>
        </div>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <div>
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Recent users</h2>
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($recentUsers as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->photo_url }}" class="h-8 w-8 rounded-full object-cover" alt="">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <x-badge :color="$user->role === 'admin' ? 'purple' : ($user->role === 'employer' ? 'blue' : 'green')">{{ $user->role }}</x-badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.users') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">View all users &rarr;</a>
            </div>
        </div>

        <div>
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Jobs by category</h2>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                @foreach ($jobsByCategory as $item)
                    <div class="mb-3">
                        <div class="mb-1 flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-300">{{ $item->category?->name ?? 'Uncategorized' }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $item->total }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                            @php $pct = $jobsByCategory->max('total') ? round($item->total / $jobsByCategory->max('total') * 100) : 0; @endphp
                            <div class="h-2 rounded-full bg-primary-600" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Recent applications</h2>
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Candidate</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Job</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach ($recentApplications as $application)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $application->user->name }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $application->job->title }}</td>
                            <td class="px-5 py-3">
                                <x-badge :color="\App\Support\JobMeta::applicationStatusColor($application->status)">
                                    {{ \App\Support\JobMeta::applicationStatuses()[$application->status] ?? $application->status }}
                                </x-badge>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $application->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
