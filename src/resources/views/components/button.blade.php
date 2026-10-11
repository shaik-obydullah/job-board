@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $variants = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus-visible:outline-primary-600',
        'secondary' => 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700',
        'outline' => 'border border-primary-600 text-primary-700 hover:bg-primary-50 dark:text-primary-300 dark:hover:bg-primary-900/30',
        'ghost' => 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'success' => 'bg-green-600 text-white hover:bg-green-700',
    ];
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition-colors focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
