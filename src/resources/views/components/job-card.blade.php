@props(['job'])

@php
    $types = \App\Support\JobMeta::jobTypes();
    $levels = \App\Support\JobMeta::experienceLevels();
@endphp

<div class="group flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 transition hover:border-primary-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-primary-700">
    <div class="flex items-start gap-4">
        <a href="{{ route('companies.show', $job->company) }}" class="shrink-0">
            @if ($job->company->logo)
                <img src="{{ asset('storage/' . $job->company->logo) }}" alt="{{ $job->company->name }}" class="h-12 w-12 rounded-xl object-cover">
            @else
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                    <span class="text-lg font-bold">{{ strtoupper(substr($job->company->name, 0, 1)) }}</span>
                </span>
            @endif
        </a>
        <div class="min-w-0 flex-1">
            <a href="{{ route('jobs.show', $job) }}" class="text-base font-semibold text-gray-900 group-hover:text-primary-600 dark:text-white">
                {{ $job->title }}
            </a>
            <a href="{{ route('companies.show', $job->company) }}" class="block text-sm text-gray-500 hover:text-primary-600 dark:text-gray-400">
                {{ $job->company->name }}
            </a>
            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    {{ $job->location }}
                </span>
                <span class="inline-flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    {{ $types[$job->job_type] ?? $job->job_type }}
                </span>
            </div>
        </div>
        <div class="flex flex-col items-end gap-2">
            <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">{{ $job->salary_label }}</span>
            @if ($job->is_featured)
                <x-badge color="amber">Featured</x-badge>
            @endif
        </div>
    </div>
    <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
        <div class="flex flex-wrap gap-2">
            <x-badge color="primary">{{ $levels[$job->experience_level] ?? $job->experience_level }}</x-badge>
            <x-badge color="gray">{{ \App\Support\JobMeta::locationTypes()[$job->location_type] ?? $job->location_type }}</x-badge>
        </div>
        <span class="text-xs text-gray-400 dark:text-gray-500">Posted {{ $job->created_at->diffForHumans() }}</span>
    </div>
</div>
