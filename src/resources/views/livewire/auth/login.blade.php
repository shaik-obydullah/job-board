<div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600 text-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </span>
            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </a>
        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Sign in to your account</h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            New here?
            <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">Create an account</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <x-alert type="error" :title="count($errors) ? $errors->first() : null">
                {{ count($errors) ? $errors->first() : '' }}
            </x-alert>

            <form wire:submit="login" class="space-y-4">
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                    <input type="email" wire:model="email" id="email" autocomplete="email" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-primary-600 hover:text-primary-500">Forgot password?</a>
                    </div>
                    <input type="password" wire:model="password" id="password" autocomplete="current-password" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <input type="checkbox" wire:model="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800">
                        Remember me
                    </label>
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="login"
                        class="flex w-full items-center justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                    <span wire:loading.remove wire:target="login">Sign in</span>
                    <span wire:loading wire:target="login">Signing in...</span>
                </button>
            </form>

            <div class="mt-6 rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4 text-xs text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <p class="font-semibold text-gray-700 dark:text-gray-300">Demo accounts</p>
                <p class="mt-1">Seeker: <code class="text-primary-600">seeker@jobboard.test</code> / <code>password</code></p>
                <p>Employer: <code class="text-primary-600">employer@jobboard.test</code> / <code>password</code></p>
                <p>Admin: <code class="text-primary-600">admin@jobboard.test</code> / <code>password</code></p>
            </div>
        </div>
    </div>
</div>
