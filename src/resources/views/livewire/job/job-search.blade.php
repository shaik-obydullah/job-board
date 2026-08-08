<div>
    <x-breadcrumbs :items="[
        ['label' => 'Jobs'],
    ]" />

    {{-- Header --}}
    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Find your next job</h1>
            <form wire:submit.prevent class="mt-5 grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
                <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Job title, skill, or keyword"
                           class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
                </div>
                <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <input type="text" wire:model.live.debounce.300ms="location" placeholder="City or remote"
                           class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
                </div>
                <button type="submit" class="rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-700">Search</button>
            </form>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-8 lg:flex-row">
            {{-- Filters sidebar --}}
            <aside class="lg:w-72 lg:shrink-0">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 lg:sticky lg:top-20 dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900 dark:text-white">Filters</h2>
                        <button wire:click="clearFilters" class="text-xs font-medium text-primary-600 hover:text-primary-500">Clear all</button>
                    </div>

                    {{-- Category --}}
                    <div class="border-t border-gray-100 py-5 first:border-t-0 dark:border-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Category</h3>
                        <div class="max-h-56 space-y-2 overflow-y-auto">
                            <label class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                                <input type="radio" name="category" wire:model.live.change="category" value=""
                                       class="rounded-full border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                                All categories
                            </label>
                            @foreach ($this->categories as $cat)
                                <label class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                                    <input type="radio" name="category" wire:model.live.change="category" value="{{ $cat->slug }}"
                                           class="rounded-full border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                                    {{ $cat->name }}
                                    <span class="ml-auto text-xs text-gray-400">{{ $cat->jobs_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Job type --}}
                    <div class="border-t border-gray-100 py-5 dark:border-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Job type</h3>
                        <div class="space-y-2">
                            @foreach ($jobTypes as $value => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                                    <input type="checkbox" wire:model.live.change="types" value="{{ $value }}"
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Experience level --}}
                    <div class="border-t border-gray-100 py-5 dark:border-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Experience</h3>
                        <div class="space-y-2">
                            @foreach ($experienceLevels as $value => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                                    <input type="checkbox" wire:model.live.change="levels" value="{{ $value }}"
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Location type --}}
                    <div class="border-t border-gray-100 py-5 dark:border-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Location type</h3>
                        <div class="space-y-2">
                            @foreach ($locationTypeOptions as $value => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                                    <input type="checkbox" wire:model.live.change="locationTypes" value="{{ $value }}"
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Salary --}}
                    <div class="border-t border-gray-100 py-5 dark:border-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Minimum salary</h3>
                        <input type="range" wire:model.live.change="salary" min="0" max="{{ $maxSalary }}" step="5000"
                               class="w-full accent-primary-600">
                        <div class="mt-1 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $salary > 0 ? '$' . number_format($salary) . '+' : 'Any salary' }}</span>
                            <span>${{ number_format($maxSalary) }}+</span>
                        </div>
                    </div>

                    {{-- Posted date --}}
                    <div class="border-t border-gray-100 py-5 dark:border-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Date posted</h3>
                        <div class="space-y-2">
                            @foreach (['today' => 'Today', 'week' => 'Past week', 'month' => 'Past month'] as $value => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                                    <input type="radio" name="posted" wire:model.live.change="posted" value="{{ $value }}"
                                           class="rounded-full border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                                    {{ $label }}
                                </label>
                            @endforeach
                            <label class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                                <input type="radio" name="posted" wire:model.live.change="posted" value=""
                                       class="rounded-full border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                                Any time
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Results --}}
            <div class="flex-1">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $jobs->firstItem() ?? 0 }}-{{ $jobs->lastItem() ?? 0 }}</span>
                        of <span class="font-semibold text-gray-900 dark:text-white">{{ $jobs->total() }}</span> jobs
                    </p>
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-gray-500 dark:text-gray-400">Sort by</label>
                        <select wire:model.live.change="sort" class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="latest">Newest</option>
                            <option value="oldest">Oldest</option>
                            <option value="salary">Highest salary</option>
                        </select>
                    </div>
                </div>

                {{-- Active filter chips --}}
                @if ($category || count($types) || count($levels) || $salary > 0)
                    <div class="mb-6 flex flex-wrap gap-2">
                        @if ($category)
                            <span class="inline-flex items-center gap-1 rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                                {{ $this->categories->firstWhere('slug', $category)?->name ?? $category }}
                                <button wire:click="setCategory('')" class="text-primary-500 hover:text-primary-700">×</button>
                            </span>
                        @endif
                        @foreach ($types as $type)
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                {{ $jobTypes[$type] ?? $type }}
                                <button wire:click="$set('types', {{ json_encode(array_values(array_diff($types, [$type]))) }})" class="text-gray-400 hover:text-gray-600">×</button>
                            </span>
                        @endforeach
                        @if ($salary > 0)
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                ${{ number_format($salary) }}+
                                <button wire:click="$set('salary', 0)" class="text-gray-400 hover:text-gray-600">×</button>
                            </span>
                        @endif
                    </div>
                @endif

                @if ($jobs->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-16 text-center dark:border-gray-700 dark:bg-gray-900">
                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No jobs found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or clearing filters.</p>
                        <button wire:click="clearFilters" class="mt-4 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Clear filters</button>
                    </div>
                @else
                    <div class="space-y-5">
                        @foreach ($jobs as $job)
                            <x-job-card :job="$job" />
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $jobs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
