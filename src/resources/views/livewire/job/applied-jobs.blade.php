<div>
    <x-alert type="success">{{ session('success') }}</x-alert>
    <x-alert type="error">{{ session('error') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Applied jobs</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track the status of your applications.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($applications->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 p-16 text-center dark:border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No applications yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start applying to jobs to track your applications here.</p>
                <a href="{{ route('jobs.index') }}" class="mt-4 inline-block rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Browse jobs</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($applications as $application)
                    @php
                        $statuses = \App\Support\JobMeta::applicationStatuses();
                        $step = array_search($application->status, array_keys($statuses), true);
                    @endphp
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('companies.show', $application->job->company) }}" class="shrink-0">
                                    @if ($application->job->company->logo)
                                        <img src="{{ asset('storage/' . $application->job->company->logo) }}" class="h-12 w-12 rounded-xl object-cover" alt="">
                                    @else
                                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($application->job->company->name, 0, 1)) }}</span>
                                    @endif
                                </a>
                                <div>
                                    <a href="{{ route('jobs.show', $application->job) }}" class="text-base font-semibold text-gray-900 hover:text-primary-600 dark:text-white">{{ $application->job->title }}</a>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $application->job->company->name }} · Applied {{ $application->created_at->diffForHumans() }}</p>
                                    <div class="mt-2">
                                        <x-badge :color="\App\Support\JobMeta::applicationStatusColor($application->status)">
                                            {{ $statuses[$application->status] ?? $application->status }}
                                        </x-badge>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <a href="{{ route('jobs.show', $application->job) }}" class="text-sm font-semibold text-primary-600 hover:text-primary-500">View job</a>
                                @if (in_array($application->status, ['pending', 'reviewed'], true) && $application->job->isOpen())
                                    <button wire:click="withdraw('{{ $application->id }}')" wire:confirm="Withdraw this application?" class="text-sm text-gray-400 hover:text-red-600">Withdraw</button>
                                @endif
                            </div>
                        </div>

                        {{-- Status pipeline --}}
                        <div class="mt-5 border-t border-gray-100 pt-5 dark:border-gray-800">
                            <div class="flex items-center gap-1">
                                @foreach (array_keys($statuses) as $index => $status)
                                    @if ($status === 'withdrawn') @continue @endif
                                    @php
                                        $currentIndex = array_search($application->status, array_keys($statuses), true);
                                        $isReached = $index <= $currentIndex;
                                        $isRejected = $application->status === 'rejected';
                                        $isHired = $application->status === 'hired';
                                    @endphp
                                    <div class="flex flex-1 flex-col items-center">
                                        <div class="flex w-full items-center">
                                            <div class="h-1 flex-1 rounded {{ $index > 0 ? ($isReached && ! $isRejected ? 'bg-primary-500' : 'bg-gray-200 dark:bg-gray-700') : 'bg-transparent' }}"></div>
                                            <span class="{{ $isReached && ! $isRejected ? 'bg-primary-500 text-white' : 'bg-gray-200 text-gray-500 dark:bg-gray-700' }} flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="h-1 flex-1 rounded {{ $index < 4 ? ($index < $currentIndex && ! $isRejected ? 'bg-primary-500' : 'bg-gray-200 dark:bg-gray-700') : 'bg-transparent' }}"></div>
                                        </div>
                                        <span class="mt-1.5 text-[10px] font-medium text-gray-500 sm:text-xs dark:text-gray-400">{{ $statuses[$status] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
