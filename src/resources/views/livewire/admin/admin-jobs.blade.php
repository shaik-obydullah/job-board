<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jobs</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review and moderate all job listings.</p>
        </div>
        <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search jobs..."
                   class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
        </div>
    </div>

    <div class="mb-4 flex gap-2">
        <button wire:click="$set('statusFilter', '')"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $statusFilter === '' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">All</button>
        @foreach (\App\Models\Job::STATUSES as $status)
            <button wire:click="$set('statusFilter', '{{ $status }}')"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $statusFilter === $status ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">
                {{ ucfirst($status) }}
            </button>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Job</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Company</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Applicants</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Posted</th>
                    <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @foreach ($jobs as $job)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-5 py-4">
                            <a href="{{ route('jobs.show', $job) }}" class="text-sm font-medium text-gray-900 hover:text-primary-600 dark:text-white">{{ $job->title }}</a>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $job->category?->name ?? '—' }} &middot; {{ $job->job_type }}</p>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $job->company?->name }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $job->applications_count }}</td>
                        <td class="px-5 py-4">
                            <select wire:change="updateStatus({{ $job->id }}, $event.target.value)"
                                    class="rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 focus:border-primary-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                @foreach (\App\Models\Job::STATUSES as $status)
                                    <option value="{{ $status }}" @selected($job->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $job->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('jobs.show', $job) }}" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700" title="View">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('company.jobs.edit', $job) }}" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
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
</div>
