<div>
    <div class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-4 text-center sm:flex-row sm:text-left">
                <img src="{{ $user->photo_url }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-white shadow dark:ring-gray-800" alt="">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    @if ($user->headline)
                        <p class="mt-1 text-sm font-medium text-primary-600 dark:text-primary-400">{{ $user->headline }}</p>
                    @endif
                    <div class="mt-2 flex flex-wrap items-center justify-center gap-3 text-sm text-gray-500 dark:text-gray-400 sm:justify-start">
                        @if ($user->location)
                            <span class="inline-flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $user->location }}
                            </span>
                        @endif
                        @if ($user->website)
                            <a href="{{ $user->website }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 010 5.656l-4 4a4 4 0 01-5.656-5.657l1.889-1.889M10.172 13.828a4 4 0 010-5.656l4-4a4 4 0 015.657 5.657l-1.889 1.889" /></svg>
                                Website
                            </a>
                        @endif
                        @if ($user->linkedin)
                            <a href="{{ $user->linkedin }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-500">LinkedIn</a>
                        @endif
                        @if ($user->github)
                            <a href="{{ $user->github }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-500">GitHub</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                @if ($user->summary)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">About</h2>
                        <p class="prose-content text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $user->summary }}</p>
                    </div>
                @endif

                @if ($user->employments->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Work Experience</h2>
                        <div class="space-y-5">
                            @foreach ($user->employments as $employment)
                                <div class="relative border-l-2 border-gray-100 pl-4 dark:border-gray-800">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employment->job_title }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $employment->company_name }} @if ($employment->location)&middot; {{ $employment->location }}@endif</p>
                                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                        {{ $employment->start_date?->format('M Y') ?? '—' }} - {{ $employment->is_current ? 'Present' : ($employment->end_date?->format('M Y') ?? '—') }}
                                    </p>
                                    @if ($employment->description)
                                        <p class="prose-content mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $employment->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->educations->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Education</h2>
                        <div class="space-y-5">
                            @foreach ($user->educations as $education)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $education->school }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @if ($education->degree){{ $education->degree }}@endif
                                        @if ($education->field_of_study)&middot; {{ $education->field_of_study }}@endif
                                    </p>
                                    @if ($education->description)
                                        <p class="prose-content mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $education->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->portfolios->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Portfolio</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($user->portfolios as $portfolio)
                                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $portfolio->title }}</h3>
                                    @if ($portfolio->description)
                                        <p class="mt-1 text-xs leading-relaxed text-gray-500 dark:text-gray-400">{{ $portfolio->description }}</p>
                                    @endif
                                    @if ($portfolio->url)
                                        <a href="{{ $portfolio->url }}" target="_blank" rel="noopener" class="mt-2 inline-block text-xs font-medium text-primary-600 hover:text-primary-500">View project &rarr;</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->awards->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Awards & Honors</h2>
                        <div class="space-y-3">
                            @foreach ($user->awards as $award)
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $award->title }}</h3>
                                        @if ($award->issuer)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $award->issuer }}</p>
                                        @endif
                                    </div>
                                    @if ($award->date)
                                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $award->date->format('M Y') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->memberships->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Memberships</h2>
                        <div class="space-y-3">
                            @foreach ($user->memberships as $membership)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $membership->organization }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @if ($membership->role){{ $membership->role }}@endif
                                        @if ($membership->year)&middot; {{ $membership->year }}@endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->references->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">References</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($user->references as $reference)
                                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-800/60">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $reference->name }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @if ($reference->job_title){{ $reference->job_title }}@endif
                                        @if ($reference->company_name)&middot; {{ $reference->company_name }}@endif
                                    </p>
                                    @if ($reference->relationship)
                                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $reference->relationship }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h2 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Skills</h2>
                    @if ($user->skills->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user->skills as $skill)
                                <span class="rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No skills listed.</p>
                    @endif
                </div>

                @if ($user->certifications->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Certifications</h2>
                        <div class="space-y-3">
                            @foreach ($user->certifications as $certification)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $certification->name }}</h3>
                                    @if ($certification->issuing_organization)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $certification->issuing_organization }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->languages->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Languages</h2>
                        <div class="space-y-2">
                            @foreach ($user->languages as $language)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $language->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \App\Models\Language::PROFICIENCIES[$language->proficiency] ?? $language->proficiency }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($user->hobbies->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <h2 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Hobbies & Interests</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user->hobbies as $hobby)
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ $hobby->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
