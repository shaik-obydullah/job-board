<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Companies</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage company profiles and verification.</p>
        </div>
        <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search companies..."
                   class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
        </div>
    </div>

    <div class="mb-4 flex gap-2">
        <button wire:click="$set('verificationFilter', '')"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $verificationFilter === '' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">All</button>
        <button wire:click="$set('verificationFilter', 'verified')"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $verificationFilter === 'verified' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">Verified</button>
        <button wire:click="$set('verificationFilter', 'unverified')"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $verificationFilter === 'unverified' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">Unverified</button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Company</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Owner</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Jobs</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Followers</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Verified</th>
                    <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @foreach ($companies as $company)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if ($company->logo)
                                    <img src="{{ asset('storage/'.$company->logo) }}" class="h-9 w-9 rounded-lg object-cover" alt="">
                                @else
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-100 text-sm font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                                @endif
                                <div>
                                    <a href="{{ route('companies.show', $company) }}" class="text-sm font-medium text-gray-900 hover:text-primary-600 dark:text-white">{{ $company->name }}</a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $company->industry ?? '—' }} &middot; {{ $company->location ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $company->user?->name ?? '—' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $company->jobs_count }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $company->followers_count }}</td>
                        <td class="px-5 py-4">
                            <x-badge :color="$company->is_verified ? 'green' : 'gray'">{{ $company->is_verified ? 'Verified' : 'Pending' }}</x-badge>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="toggleVerified({{ $company->id }})"
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $company->is_verified ? 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300' : 'bg-green-600 text-white hover:bg-green-700' }}">
                                    {{ $company->is_verified ? 'Unverify' : 'Verify' }}
                                </button>
                                <button wire:click="toggleActive({{ $company->id }})"
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $company->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-950 dark:text-red-300' : 'bg-primary-600 text-white hover:bg-primary-700' }}">
                                    {{ $company->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $companies->links() }}
    </div>
</div>
