<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Saved jobs</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Jobs you've bookmarked for later.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($jobs->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 p-16 text-center dark:border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No saved jobs yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bookmark jobs you're interested in to find them here later.</p>
                <a href="{{ route('jobs.index') }}" class="mt-4 inline-block rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Browse jobs</a>
            </div>
        @else
            <div class="grid gap-5 lg:grid-cols-2">
                @foreach ($jobs as $job)
                    <div class="relative">
                        <x-job-card :job="$job" />
                        <button wire:click="remove('{{ $job->id }}')"
                                class="absolute right-3 top-3 rounded-lg bg-white/90 p-2 text-gray-400 shadow-sm ring-1 ring-gray-200 transition hover:text-red-600 dark:bg-gray-800/90 dark:ring-gray-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
