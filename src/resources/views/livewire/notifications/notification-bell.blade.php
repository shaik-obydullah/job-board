<div x-data="{ open: false }" @keydown.escape.window="open = false" class="relative">
    <button @click="open = !open" class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" aria-label="Notifications">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute right-1 top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-transition
         class="absolute right-0 z-50 mt-2 w-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-700">
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</p>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs font-medium text-primary-600 hover:text-primary-500">Mark all read</button>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto">
            @if ($notifications->isEmpty())
                <p class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">No notifications yet.</p>
            @else
                @foreach ($notifications as $notification)
                    <a href="{{ data_get($notification->data, 'url', route('notifications.index')) }}"
                       wire:click="$dispatch('notification-read', { id: '{{ $notification->id }}' })"
                       class="flex gap-3 border-b border-gray-50 px-4 py-3 transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-700/50">
                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $notification->read_at ? 'bg-gray-300 dark:bg-gray-600' : 'bg-primary-500' }}"></span>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 {{ $notification->read_at ? '' : 'font-semibold' }} dark:text-white">
                                {{ data_get($notification->data, 'title', 'Notification') }}
                            </p>
                            <p class="mt-0.5 line-clamp-2 text-xs text-gray-500 dark:text-gray-400">{{ data_get($notification->data, 'message', '') }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        <div class="border-t border-gray-100 px-4 py-2 dark:border-gray-700">
            <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-semibold text-primary-600 hover:text-primary-500">View all notifications</a>
        </div>
    </div>

    <script>
        window.addEventListener('notification-read', () => {});
    </script>
</div>
