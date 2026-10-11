@props(['type' => 'success', 'title' => null])

@php
    $types = [
        'success' => ['border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200', 'check-circle'],
        'error' => ['border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200', 'exclamation'],
        'warning' => ['border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-200', 'exclamation'],
        'info' => ['border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-200', 'info'],
    ];
    [$classes, $icon] = $types[$type];
@endphp

@if ($slot->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'mb-4 flex items-start gap-3 rounded-lg border p-4 ' . $classes]) }}>
        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            @if ($icon === 'check-circle')
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            @elseif ($icon === 'exclamation')
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            @endif
        </svg>
        <div class="text-sm">
            @if ($title)
                <p class="font-semibold">{{ $title }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>
@endif
