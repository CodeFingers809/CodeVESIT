<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside 
                class="fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="h-full flex flex-col bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border-r border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                    <!-- Logo -->
                    <div class="flex items-center justify-between h-16 px-6 border-b border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                            <svg class="w-8 h-8 text-gruvbox-light-orange dark:text-gruvbox-dark-orange" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                            </svg>
                            <span class="text-lg font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">CSI-VESIT</span>
                        </a>
                        <button @click="sidebarOpen = false" class="lg:hidden text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 text-gruvbox-light-orange dark:text-gruvbox-dark-orange' : 'text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <!-- Study Groups -->
                        <a href="{{ route('study-groups.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('study-groups.*') ? 'bg-gruvbox-light-blue/20 dark:bg-gruvbox-dark-blue/20 text-gruvbox-light-blue dark:text-gruvbox-dark-blue' : 'text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="font-medium">Study Groups</span>
                        </a>

                        <!-- Forums -->
                        <a href="{{ route('forums.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('forums.*') ? 'bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 text-gruvbox-light-green dark:text-gruvbox-dark-green' : 'text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            <span class="font-medium">Forums</span>
                        </a>

                        <!-- Blogs -->
                        <a href="{{ route('blogs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('blogs.*') ? 'bg-gruvbox-light-purple/20 dark:bg-gruvbox-dark-purple/20 text-gruvbox-light-purple dark:text-gruvbox-dark-purple' : 'text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <span class="font-medium">Blogs</span>
                        </a>

                        <!-- Events -->
                        <a href="{{ route('events.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('events.*') ? 'bg-gruvbox-light-aqua/20 dark:bg-gruvbox-dark-aqua/20 text-gruvbox-light-aqua dark:text-gruvbox-dark-aqua' : 'text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">Events</span>
                        </a>

                        <!-- Personal Calendar -->
                        <a href="{{ route('calendar.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('calendar.*') ? 'bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow' : 'text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium">My Calendar</span>
                        </a>

                        @if(auth()->user()->isAdmin())
                        <!-- Admin Panel -->
                        <div class="pt-4 border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.*') ? 'bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20 text-gruvbox-light-red dark:text-gruvbox-dark-red' : 'text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium">Admin Panel</span>
                            </a>
                        </div>
                        @endif
                    </nav>

                    <!-- User Profile & Theme Toggle -->
                    <div class="p-4 border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 uppercase">Theme</span>
                            <button @click="darkMode = !darkMode" class="p-2 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors">
                                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-3 p-3 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2">
                            <img src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-lg">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gruvbox-light-red dark:text-gruvbox-dark-red hover:text-gruvbox-light-red/80 dark:hover:text-gruvbox-dark-red/80">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-h-screen">
                <!-- Top Bar (Mobile) -->
                <header class="lg:hidden sticky top-0 z-40 bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border-b border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                    <div class="flex items-center justify-between h-16 px-4">
                        <button @click="sidebarOpen = true" class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <span class="text-lg font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">CSI-VESIT</span>
                        <div class="w-6"></div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden">
                    @if (isset($header))
                        <div class="bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border-b border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </div>
                    @endif

                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <!-- Success Message -->
                            @if (session('success'))
                                <div class="mb-4 p-4 rounded-lg bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 border border-gruvbox-light-green dark:border-gruvbox-dark-green">
                                    <p class="text-gruvbox-light-green dark:text-gruvbox-dark-green font-medium">{{ session('success') }}</p>
                                </div>
                            @endif

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="mb-4 p-4 rounded-lg bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20 border border-gruvbox-light-red dark:border-gruvbox-dark-red">
                                    <ul class="list-disc list-inside text-gruvbox-light-red dark:text-gruvbox-dark-red">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
            style="display: none;"
        ></div>
    </body>
</html>
