@props(['items' => []])

<div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary-600">Home</a>
            @foreach ($items as $item)
                <span class="text-gray-300 dark:text-gray-600">/</span>
                @if (! empty($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-primary-600">{{ $item['label'] }}</a>
                @else
                    <span class="text-gray-900 dark:text-white">{{ $item['label'] }}</span>
                @endif
            @endforeach
        </nav>
    </div>
</div>
