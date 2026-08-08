<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Job Categories</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage job categories used across the platform.</p>
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-5">
        <form wire:submit="{{ $editingId ? 'update' : 'create' }}" class="rounded-2xl border border-gray-200 bg-white p-6 lg:col-span-2 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ $editingId ? 'Edit category' : 'New category' }}</h2>
            <x-input label="Name" name="name" wire:model="name" required placeholder="e.g. Engineering" />
            <x-input label="Icon" name="icon" wire:model="icon" placeholder="e.g. cog" />
            <x-textarea label="Description" name="description" wire:model="description" rows="2" />
            <label class="mb-4 flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" wire:model="is_active" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                Active
            </label>
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    {{ $editingId ? 'Update category' : 'Create category' }}
                </button>
                @if ($editingId)
                    <button type="button" wire:click="cancelEdit" class="rounded-lg bg-white px-5 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">Cancel</button>
                @endif
            </div>
        </form>

        <div class="lg:col-span-3">
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($categories as $category)
                    <div class="flex items-start justify-between gap-3 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div>
                            <div class="flex items-center gap-2">
                                @if ($category->icon)
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-900/40 dark:text-primary-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                    </span>
                                @endif
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $category->name }}</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $category->jobs_count }} jobs</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-1">
                            <button wire:click="edit({{ $category->id }})" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700" title="Edit">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button wire:click="toggleActive({{ $category->id }})" class="rounded-lg p-1.5 {{ $category->is_active ? 'text-gray-400 hover:bg-gray-100 hover:text-red-600 dark:hover:bg-gray-700' : 'text-green-500 hover:bg-gray-100 dark:hover:bg-gray-700' }}" title="Toggle active">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
