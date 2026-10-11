<div>
    <x-alert type="success">{{ session('success') }}</x-alert>

    <x-breadcrumbs :items="[
        ['label' => 'Companies', 'url' => route('companies.index')],
        ['label' => $company->name],
    ]" />

    {{-- Company banner --}}
    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        @if ($company->banner)
            <div class="relative h-40 w-full bg-cover bg-center" style="background-image: url('{{ asset('storage/'.$company->banner) }}')">
                <div class="flex h-full flex-col justify-end bg-gradient-to-t from-black/60 to-transparent">
                    <div class="mx-auto flex w-full max-w-7xl flex-col gap-4 px-4 pb-4 sm:flex-row sm:items-end sm:justify-between sm:px-6 lg:px-8">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                            @if ($company->logo)
                                <img src="{{ asset('storage/'.$company->logo) }}" class="h-20 w-20 rounded-2xl border-4 border-white bg-white object-cover shadow dark:border-gray-900 dark:bg-gray-900" alt="">
                            @else
                                <span class="flex h-20 w-20 items-center justify-center rounded-2xl border-4 border-white bg-primary-100 text-3xl font-bold text-primary-700 shadow dark:border-gray-900 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                            @endif
                            <div style="margin-top: 2rem; color: white;">
                                <h1 class="flex items-center gap-2 text-2xl font-bold text-white drop-shadow" style="margin-top: 1rem;">
                                    {{ $company->name }}
                                    @if ($company->is_verified) <x-badge color="green">Verified</x-badge> @endif
                                </h1>
                                <p class="mt-1 text-sm text-white/90 drop-shadow">
                                    {{ $company->industry ?? 'General' }} &middot; {{ $company->location ?? 'Location not set' }}
                                    &middot; {{ $followerCount }} followers
                                </p>
                            </div>
                        </div>
                        <button wire:click="toggleFollow"
                                class="{{ $isFollowing ? 'rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700' : 'rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700' }}">
                            {{ $isFollowing ? 'Following' : '+ Follow' }}
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="h-40 w-full bg-gradient-to-r from-primary-600 to-primary-400">
                <div class="mx-auto flex w-full max-w-7xl flex-col gap-4 px-4 pb-4 sm:flex-row sm:items-end sm:justify-between sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                        @if ($company->logo)
                            <img src="{{ asset('storage/'.$company->logo) }}" class="h-20 w-20 rounded-2xl border-4 border-white bg-white object-cover shadow dark:border-gray-900 dark:bg-gray-900" alt="">
                        @else
                            <span class="flex h-20 w-20 items-center justify-center rounded-2xl border-4 border-white bg-primary-100 text-3xl font-bold text-primary-700 shadow dark:border-gray-900 dark:bg-primary-900/40 dark:text-primary-300">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                        @endif
                        <div style="margin-top: 2rem; color: white;">
                            <h1 class="flex items-center gap-2 text-2xl font-bold text-white drop-shadow" style="margin-top: 1rem;">
                                {{ $company->name }}
                                @if ($company->is_verified) <x-badge color="green">Verified</x-badge> @endif
                            </h1>
                            <p class="mt-1 text-sm text-white/90 drop-shadow">
                                {{ $company->industry ?? 'General' }} &middot; {{ $company->location ?? 'Location not set' }}
                                &middot; {{ $followerCount }} followers
                            </p>
                        </div>
                    </div>
                    <button wire:click="toggleFollow"
                            class="{{ $isFollowing ? 'rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700' : 'rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700' }}">
                        {{ $isFollowing ? 'Following' : '+ Follow' }}
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                @if ($company->description)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">About us</h2>
                        <div class="prose-content text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $company->description }}</div>
                    </div>
                @endif

                @if ($company->mission)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Our mission</h2>
                        <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $company->mission }}</p>
                    </div>
                @endif

                <div>
                    <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Open positions ({{ $jobs->total() }})</h2>
                    @if ($jobs->isEmpty())
                        <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">No open positions right now.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($jobs as $job)
                                <x-job-card :job="$job" />
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">Company info</h3>
                    <dl class="space-y-3 text-sm">
                        @if ($company->website)
                            <div class="flex justify-between gap-2">
                                <dt class="text-gray-500 dark:text-gray-400">Website</dt>
                                <dd><a href="{{ $company->website }}" target="_blank" rel="noopener" class="font-medium text-primary-600 hover:text-primary-500">Visit site</a></dd>
                            </div>
                        @endif
                        @if ($company->size)
                            <div class="flex justify-between gap-2">
                                <dt class="text-gray-500 dark:text-gray-400">Company size</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $company->size }}</dd>
                            </div>
                        @endif
                        @if ($company->founded_year)
                            <div class="flex justify-between gap-2">
                                <dt class="text-gray-500 dark:text-gray-400">Founded</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $company->founded_year }}</dd>
                            </div>
                        @endif
                        @if ($company->email)
                            <div class="flex justify-between gap-2">
                                <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $company->email }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
