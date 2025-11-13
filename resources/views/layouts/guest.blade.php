<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="flex items-center space-x-2 mb-6">
                <svg class="w-12 h-12 text-gruvbox-light-orange dark:text-gruvbox-dark-orange" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="text-2xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">CSI-VESIT</span>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 shadow-lg overflow-hidden sm:rounded-lg border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                {{ $slot }}
            </div>

            <!-- Theme Toggle -->
            <div class="mt-6">
                <button @click="darkMode = !darkMode" class="p-3 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2 transition-colors">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </body>
</html>
