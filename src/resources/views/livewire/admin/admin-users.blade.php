<div>
    <x-alert type="success">{{ session('success') }}</x-alert>
    <x-alert type="error">{{ session('error') }}</x-alert>

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage user accounts and roles.</p>
        </div>
        <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
                   class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
        </div>
    </div>

    <div class="mb-4 flex gap-2">
        <button wire:click="$set('roleFilter', '')"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $roleFilter === '' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">All</button>
        @foreach (['job_seeker' => 'Seekers', 'employer' => 'Employers', 'admin' => 'Admins'] as $value => $label)
            <button wire:click="$set('roleFilter', '{{ $value }}')"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $roleFilter === $value ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700' }}">{{ $label }}</button>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">User</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Applications</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Joined</th>
                    <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->photo_url }}" class="h-9 w-9 rounded-full object-cover" alt="">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                        @if ($user->is_banned) <x-badge color="red">Banned</x-badge> @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <select wire:change="updateRole({{ $user->id }}, $event.target.value)"
                                    class="rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 focus:border-primary-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                <option value="job_seeker" @selected($user->role === 'job_seeker')>Job Seeker</option>
                                <option value="employer" @selected($user->role === 'employer')>Employer</option>
                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                            </select>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->applications_count }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('profile.public', $user->username) }}" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700" title="View profile">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                @if ($user->id !== auth()->id())
                                    <button wire:click="toggleBan({{ $user->id }})"
                                            class="rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $user->is_banned ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-950 dark:text-red-300' }}">
                                        {{ $user->is_banned ? 'Reinstate' : 'Suspend' }}
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
