<div>
    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-8 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $unreadCount }} unread</p>
            </div>
            <div class="flex gap-2">
                @if ($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">Mark all read</button>
                @endif
                <button wire:click="clearAll" wire:confirm="Delete all notifications?" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-600 ring-1 ring-inset ring-gray-300 hover:bg-red-50 dark:bg-gray-800 dark:ring-gray-700">Clear all</button>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($notifications->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 p-16 text-center dark:border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No notifications</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You're all caught up.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($notifications as $notification)
                    <div class="flex items-start gap-4 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                        <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $notification->read_at ? 'bg-gray-300 dark:bg-gray-600' : 'bg-primary-500' }}"></span>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ data_get($notification->data, 'title', 'Notification') }}</p>
                                <span class="shrink-0 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ data_get($notification->data, 'message', '') }}</p>
                            <div class="mt-3 flex items-center gap-4">
                                @if (data_get($notification->data, 'url'))
                                    <a href="{{ data_get($notification->data, 'url') }}" wire:click="markAsRead('{{ $notification->id }}')" class="text-sm font-semibold text-primary-600 hover:text-primary-500">View details →</a>
                                @endif
                                @if (! $notification->read_at)
                                    <button wire:click="markAsRead('{{ $notification->id }}')" class="text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">Mark as read</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
