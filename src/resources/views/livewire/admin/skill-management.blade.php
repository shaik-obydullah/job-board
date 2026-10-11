<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Skills</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage the skill taxonomy used in job listings and profiles.</p>
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-5">
        <form wire:submit="create" class="rounded-2xl border border-gray-200 bg-white p-6 lg:col-span-2 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Add a skill</h2>
            <x-input label="Skill name" name="name" wire:model="name" required placeholder="e.g. Machine Learning" />
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">Add skill</button>
        </form>

        <div class="lg:col-span-3">
            <div class="mb-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 focus-within:ring-2 focus-within:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search skills..."
                       class="w-full bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white">
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Skill</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Slug</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Jobs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($skills as $skill)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $skill->name }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $skill->slug }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $skill->jobs_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $skills->links() }}
            </div>
        </div>
    </div>
</div>
