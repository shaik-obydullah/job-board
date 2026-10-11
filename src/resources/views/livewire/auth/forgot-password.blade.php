<div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Forgot your password?</h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            Enter your email and we'll send you a password reset link.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <x-alert type="success">{{ $status }}</x-alert>

            <form wire:submit="sendResetLink" class="space-y-4">
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                    <input type="email" wire:model="email" id="email" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">
                    Email password reset link
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">Back to sign in</a>
            </p>
        </div>
    </div>
</div>
