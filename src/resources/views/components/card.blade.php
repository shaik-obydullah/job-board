@props(['title' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900']) }}>
    @if ($title)
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        </div>
    @endif
    <div class="px-6 py-5">
        {{ $slot }}
    </div>
    @if ($footer)
        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            {{ $footer }}
        </div>
    @endif
</div>
