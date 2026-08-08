<div>
    <x-alert type="success">{{ session('success') }}</x-alert>
    <x-alert type="error">{{ session('error') }}</x-alert>

    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Company Settings</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $company ? 'Update your company profile.' : 'Set up your company to start posting jobs.' }}</p>
        </div>
    </div>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form wire:submit="save" class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Basic information</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-input label="Company name" name="name" wire:model="name" required placeholder="Acme Inc." />
                    </div>
                    <div>
                        <x-input label="Website" name="website" wire:model="website" type="url" placeholder="https://example.com" />
                    </div>
                    <div>
                        <x-input label="Location" name="location" wire:model="location" placeholder="San Francisco, CA" />
                    </div>
                    <div>
                        <x-input label="Industry" name="industry" wire:model="industry" placeholder="Technology" />
                    </div>
                    <div>
                        <x-input label="Company size" name="size" wire:model="size" placeholder="11-50" />
                    </div>
                    <div>
                        <x-input label="Founded year" name="founded_year" wire:model="founded_year" placeholder="2015" />
                    </div>
                    <div>
                        <x-input label="Company email" name="email" wire:model="email" type="email" placeholder="hr@example.com" />
                    </div>
                    <div>
                        <x-input label="Phone" name="phone" wire:model="phone" placeholder="+1 555 000 0000" />
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">About your company</h2>
                <x-textarea label="Description" name="description" wire:model="description" rows="5" placeholder="Tell job seekers about your company..." />
                <x-textarea label="Mission" name="mission" wire:model="mission" rows="3" placeholder="What does your company do?" />
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Logo</h2>
                <div class="flex items-center gap-4">
                    @if ($existingLogo)
                        <img src="{{ asset('storage/'.$existingLogo) }}" class="h-16 w-16 rounded-xl object-cover" alt="Company logo">
                    @endif
                    <input type="file" wire:model="logo" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 dark:text-gray-400 dark:file:bg-primary-900/40 dark:file:text-primary-300">
                </div>
                @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                <div wire:loading wire:target="logo" class="mt-2 text-sm text-gray-500">Uploading...</div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('company.show') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">Cancel</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700">Save changes</button>
            </div>
        </form>
    </div>
</div>
