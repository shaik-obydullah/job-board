@props(['color' => 'gray'])

@php
    $colors = [
        'gray' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        'green' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
        'red' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
        'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
        'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
        'primary' => 'bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300',
        'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ' . $colors[$color]]) }}>
    {{ $slot }}
</span>
