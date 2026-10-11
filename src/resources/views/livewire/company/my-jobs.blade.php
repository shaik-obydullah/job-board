<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Jobs</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and track your job postings.</p>
            </div>
            <a href="{{ route('company.jobs.create') }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Post a job</a>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-2">
                @foreach (['all' => 'All', 'published' => 'Published', 'draft' => 'Draft', 'closed' => 'Closed', 'expired' => 'Expired'] as $value => $label)
                    <button wire:click="setFilter('{{ $value }}')"
                            class="rounded-full px-4 py-1.5 text-sm font-medium transition
                            {{ $filter === $value ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search jobs..."
                       class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
            </div>
        </div>

        @if ($jobs->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 p-16 text-center dark:border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No jobs found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Post your first job to start receiving applications.</p>
                <a href="{{ route('company.jobs.create') }}" class="mt-4 inline-block rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Post a job</a>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Job</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Applicants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Deadline</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($jobs as $job)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4">
                                    <a href="{{ route('jobs.show', $job) }}" class="text-sm font-semibold text-gray-900 hover:text-primary-600 dark:text-white">{{ $job->title }}</a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $job->job_type }} &middot; {{ $job->location ?? 'No location' }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <a href="{{ route('company.jobs.applicants', $job) }}" class="font-medium text-primary-600 hover:text-primary-500">{{ $job->applications_count }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($job->views_count) }}</td>
                                <td class="px-6 py-4">
                                    <x-badge :color="\App\Support\JobMeta::jobStatusColor($job->status)">
                                        {{ \App\Support\JobMeta::jobStatuses()[$job->status] ?? $job->status }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $job->deadline ? $job->deadline->format('M j, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('company.jobs.edit', $job) }}" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700" title="Edit">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <button wire:click="toggleStatus({{ $job->id }})" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700"
                                                title="{{ $job->status === 'published' ? 'Close job' : 'Publish job' }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        </button>
                                        <button wire:click="duplicate({{ $job->id }})" wire:confirm="Duplicate this job?" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700" title="Duplicate">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
