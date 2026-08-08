<div>
    <x-breadcrumbs :items="[
        ['label' => 'Companies'],
    ]" />

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Explore companies</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Discover companies hiring now and follow your favorites.</p>
            <form wire:submit.prevent class="mt-5 flex flex-col gap-3 sm:flex-row">
                <div class="flex flex-1 items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search companies..."
                           class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
                </div>
                <select wire:model.live.change="industry" class="rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">All industries</option>
                    @foreach ($this->industries as $industry)
                        <option value="{{ $industry }}">{{ $industry }}</option>
                    @endforeach
                </select>
                @if ($search || $industry)
                    <button wire:click="clearFilters" class="rounded-xl px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Clear</button>
                @endif
            </form>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($companies->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 p-16 text-center dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No companies found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search.</p>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($companies as $company)
                    <a href="{{ route('companies.show', $company) }}" class="group rounded-2xl border border-gray-200 bg-white p-6 transition hover:border-primary-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-primary-700">
                        <div class="flex items-center gap-4">
                            @if ($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" class="h-14 w-14 rounded-2xl object-cover" alt="">
                            @else
                                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-100 text-xl font-bold text-primary-700 group-hover:bg-primary-200 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-gray-900 group-hover:text-primary-600 dark:text-white">{{ $company->name }}</p>
                                <p class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $company->location ?? 'No location' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4 text-xs text-gray-500 dark:border-gray-800 dark:text-gray-400">
                            <span>{{ $company->jobs_count }} open jobs</span>
                            <span>{{ $company->followers_count }} followers</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</div>
