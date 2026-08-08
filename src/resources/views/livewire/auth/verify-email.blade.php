<div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-8 text-center shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-primary-600 dark:bg-primary-900/40 dark:text-primary-300">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </span>
            <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Verify your email</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                We sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
                Click the link in the email to activate your account.
            </p>

            <x-alert type="success">{{ session('status') }}</x-alert>

            <div class="mt-6 space-y-3">
                <button wire:click="resend" wire:loading.attr="disabled"
                        class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                    Resend verification email
                </button>
                <button wire:click="logout"
                        class="w-full rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700">
                    Sign out
                </button>
            </div>
        </div>
    </div>
</div>
