<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <title>{{ $title ?? 'Job Board' }} | {{ config('app.name', 'Job Board') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-init="dark && document.documentElement.classList.add('dark')" x-cloak>
    @livewireStyles
    <div class="min-h-screen flex flex-col">
        <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-800 dark:bg-gray-900/90">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-600 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                    </a>

                    <nav class="hidden items-center gap-1 md:flex">
                        <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                            Home
                        </a>
                        <a href="{{ route('jobs.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('jobs.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                            Find Jobs
                        </a>
                        <a href="{{ route('companies.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('companies.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                            Companies
                        </a>
                        @auth
                            @if (auth()->user()->isEmployer())
                                <a href="{{ route('company.show') }}" class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('company.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                                    My Company
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="dark = !dark; localStorage.setItem('theme', dark ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', dark)"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                            aria-label="Toggle dark mode">
                        <svg x-show="!dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    @auth
                        @livewire('notifications.notification-bell')
                    @endauth

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 rounded-full p-1 hover:ring-2 hover:ring-primary-500">
                                <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}" class="h-8 w-8 rounded-full object-cover">
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Dashboard</a>
                                    @if (auth()->user()->isJobSeeker())
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">My Profile</a>
                                        <a href="{{ route('saved-jobs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Saved Jobs</a>
                                        <a href="{{ route('applied-jobs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Applied Jobs</a>
                                    @elseif (auth()->user()->isEmployer())
                                        <a href="{{ route('company.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">My Company</a>
                                        <a href="{{ route('company.jobs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Manage Jobs</a>
                                    @elseif (auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Admin Panel</a>
                                    @endif
                                    <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Notifications</a>
                                </div>
                                <div class="border-t border-gray-100 py-1 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden text-sm font-medium text-gray-600 hover:text-gray-900 sm:block dark:text-gray-300">Sign in</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">Sign up</a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="grid gap-8 md:grid-cols-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-white">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </span>
                            <span class="font-bold">{{ config('app.name') }}</span>
                        </div>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Connecting talented professionals with great companies.</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">For Job Seekers</h3>
                        <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li><a href="{{ route('jobs.index') }}" class="hover:text-primary-600">Browse Jobs</a></li>
                            <li><a href="{{ route('companies.index') }}" class="hover:text-primary-600">Companies</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-primary-600">Create Profile</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">For Employers</h3>
                        <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li><a href="{{ route('company.settings') }}" class="hover:text-primary-600">Post a Job</a></li>
                            <li><a href="{{ route('company.jobs') }}" class="hover:text-primary-600">Manage Listings</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">About</h3>
                        <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-primary-600">Sign In</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-6 text-center text-xs text-gray-400 dark:border-gray-800 dark:text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
