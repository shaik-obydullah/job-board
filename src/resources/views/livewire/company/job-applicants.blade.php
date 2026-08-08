<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('company.jobs') }}" class="hover:text-primary-600">My Jobs</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">{{ $job->title }}</span>
            </nav>
            <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Applicants</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $applications->total() }} total &middot; {{ $counts['pending'] }} pending
                    </p>
                </div>
                <a href="{{ route('jobs.show', $job) }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">View job listing</a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('statusFilter', '')"
                        class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $statusFilter === '' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">
                    All ({{ $counts['all'] }})
                </button>
                @foreach (['pending', 'reviewed', 'shortlisted', 'rejected'] as $status)
                    <button wire:click="$set('statusFilter', '{{ $status }}')"
                            class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $statusFilter === $status ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">
                        {{ \App\Support\JobMeta::applicationStatuses()[$status] }} ({{ $counts[$status] }})
                    </button>
                @endforeach
            </div>
            <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search applicants..."
                       class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
            </div>
        </div>

        @if ($applications->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 p-16 text-center dark:border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No applicants found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Applications will appear here once job seekers apply.</p>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($applications as $application)
                    <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-start gap-3">
                            <img src="{{ $application->user->photo_url }}" class="h-11 w-11 rounded-full object-cover" alt="">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('profile.public', $application->user->username) }}" class="block truncate text-sm font-semibold text-gray-900 hover:text-primary-600 dark:text-white">
                                    {{ $application->user->name }}
                                </a>
                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $application->user->email }}</p>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Applied {{ $application->created_at->diffForHumans() }}</p>
                            </div>
                            <x-badge :color="\App\Support\JobMeta::applicationStatusColor($application->status)">
                                {{ \App\Support\JobMeta::applicationStatuses()[$application->status] ?? $application->status }}
                            </x-badge>
                        </div>

                        @if ($application->cover_letter)
                            <div class="prose-content mt-4 max-h-32 overflow-y-auto rounded-lg bg-gray-50 p-3 text-xs leading-relaxed text-gray-600 dark:bg-gray-800/60 dark:text-gray-300">
                                {{ $application->cover_letter }}
                            </div>
                        @endif

                        @if ($application->user->skills->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($application->user->skills->take(4) as $skill)
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ $skill->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                            <div class="grid grid-cols-4 gap-1.5">
                                @foreach (['reviewed', 'shortlisted', 'rejected', 'hired'] as $action)
                                    <button wire:click="openStatus({{ $application->id }}, '{{ $action }}')"
                                            wire:key="{{ $application->id }}-{{ $action }}"
                                            class="rounded-lg px-2 py-1.5 text-[11px] font-semibold transition
                                            {{ $application->status === $action ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        {{ ucfirst($action) }}
                                    </button>
                                @endforeach
                            </div>
                            @if ($showStatus && $selectedApplication?->id === $application->id)
                                <div class="mt-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-800/60">
                                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Add a note for the candidate:</p>
                                    <textarea wire:model="employerNote" rows="2" placeholder="Optional note..." class="mb-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                                    <button wire:click="setStatus({{ $application->id }}, '{{ $showStatus }}')" class="w-full rounded-lg bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-primary-700">Confirm</button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
