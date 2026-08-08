<div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600 text-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </span>
            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </a>
        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Create your account</h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">Sign in</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <form wire:submit="register" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">I am a...</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" wire:click="$set('role', 'job_seeker')"
                                class="{{ $role === 'job_seeker' ? 'border-primary-600 bg-primary-50 ring-2 ring-primary-500 dark:bg-primary-900/40' : 'border-gray-300 hover:border-primary-400 dark:border-gray-700' }} rounded-xl border-2 p-4 text-left transition">
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Looking for a job</span>
                            <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">Build your profile & apply to jobs</span>
                        </button>
                        <button type="button" wire:click="$set('role', 'employer')"
                                class="{{ $role === 'employer' ? 'border-primary-600 bg-primary-50 ring-2 ring-primary-500 dark:bg-primary-900/40' : 'border-gray-300 hover:border-primary-400 dark:border-gray-700' }} rounded-xl border-2 p-4 text-left transition">
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">I'm hiring</span>
                            <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">Post jobs & find candidates</span>
                        </button>
                    </div>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Full name</label>
                    <input type="text" wire:model="name" id="name" autocomplete="name" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                    <input type="email" wire:model="email" id="email" autocomplete="email" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" wire:model="password" id="password" autocomplete="new-password" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm password</label>
                    <input type="password" wire:model="password_confirmation" id="password_confirmation" autocomplete="new-password" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('password_confirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                @if (config('services.recaptcha.site_key'))
                    <div>
                        <input type="hidden" wire:model="recaptcha_token" name="g-recaptcha-response">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('recaptcha_token') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif

                <button type="submit" wire:loading.attr="disabled" wire:target="register"
                        class="flex w-full items-center justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                    <span wire:loading.remove wire:target="register">Create account</span>
                    <span wire:loading wire:target="register">Creating...</span>
                </button>
            </form>
            @if (config('services.recaptcha.site_key'))
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            @endif
        </div>
    </div>
</div>
