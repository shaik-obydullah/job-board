<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure site-wide settings.</p>
    </div>

    <div class="max-w-2xl space-y-6">
        <form wire:submit="saveGeneral" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">General</h2>
            <x-input label="Site name" name="site_name" wire:model="site_name" required />
            <x-input label="Tagline" name="site_tagline" wire:model="site_tagline" />
            <x-input label="Support email" name="support_email" wire:model="support_email" type="email" />
            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save general settings</button>
            </div>
        </form>

        <form wire:submit="saveListing" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Listings</h2>
            <x-input label="Jobs per page" name="jobs_per_page" wire:model="jobs_per_page" type="number" min="6" max="60" />
            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save listing settings</button>
            </div>
        </form>

        <form wire:submit="saveMaintenance" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Maintenance</h2>
            <div class="mb-4 grid gap-3 sm:grid-cols-2">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 transition has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 dark:border-gray-700 dark:has-[:checked]:bg-primary-900/40">
                    <input type="radio" wire:model="maintenance_mode" value="off" class="mt-1 h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Online</span>
                </label>
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 transition has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 dark:border-gray-700 dark:has-[:checked]:bg-primary-900/40">
                    <input type="radio" wire:model="maintenance_mode" value="on" class="mt-1 h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Maintenance mode</span>
                </label>
            </div>
            <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">Use <code class="rounded bg-gray-100 px-1 py-0.5 dark:bg-gray-800">php artisan down</code> to fully activate Laravel maintenance mode.</p>
            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save maintenance setting</button>
            </div>
        </form>
    </div>
</div>
