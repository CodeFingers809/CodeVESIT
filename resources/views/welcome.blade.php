<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSI-VESIT Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gruvbox-dark-bg0 text-gruvbox-dark-fg0">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="border-b border-gruvbox-dark-bg3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-gruvbox-dark-orange" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        <span class="text-xl font-bold text-gruvbox-dark-fg0">CSI-VESIT Platform</span>
                    </div>
                    <div class="flex gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gruvbox-dark-blue text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-gruvbox-dark-fg0 hover:text-gruvbox-dark-yellow transition-colors font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-gruvbox-dark-orange text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-medium">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="flex-1 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-gruvbox-dark-fg0 mb-6">
                        Your All-in-One
                        <span class="text-gruvbox-dark-orange">College Platform</span>
                    </h1>
                    <p class="text-xl text-gruvbox-dark-fg2 mb-8 max-w-2xl mx-auto">
                        Study groups, forums, blogs, and events - everything you need to organize your college life in one place.
                    </p>
                    @guest
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-gruvbox-dark-orange text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold text-lg">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-gruvbox-dark-bg1 text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-dark-bg2 transition-colors font-semibold text-lg">
                                Sign In
                            </a>
                        </div>
                    @endguest
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-16">
                    <div class="p-6 rounded-lg bg-gruvbox-dark-bg1 border border-gruvbox-dark-bg3">
                        <div class="w-12 h-12 rounded-lg bg-gruvbox-dark-blue/20 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-gruvbox-dark-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gruvbox-dark-fg0 mb-2">Study Groups</h3>
                        <p class="text-sm text-gruvbox-dark-fg3">Collaborate with classmates, share todos, and stay organized with dedicated study group channels.</p>
                    </div>

                    <div class="p-6 rounded-lg bg-gruvbox-dark-bg1 border border-gruvbox-dark-bg3">
                        <div class="w-12 h-12 rounded-lg bg-gruvbox-dark-green/20 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-gruvbox-dark-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gruvbox-dark-fg0 mb-2">Forums</h3>
                        <p class="text-sm text-gruvbox-dark-fg3">Engage in college-wide discussions and connect with students across all departments.</p>
                    </div>

                    <div class="p-6 rounded-lg bg-gruvbox-dark-bg1 border border-gruvbox-dark-bg3">
                        <div class="w-12 h-12 rounded-lg bg-gruvbox-dark-purple/20 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-gruvbox-dark-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gruvbox-dark-fg0 mb-2">Blogs</h3>
                        <p class="text-sm text-gruvbox-dark-fg3">Share your knowledge and experiences through moderated blog posts visible to the entire college.</p>
                    </div>

                    <div class="p-6 rounded-lg bg-gruvbox-dark-bg1 border border-gruvbox-dark-bg3">
                        <div class="w-12 h-12 rounded-lg bg-gruvbox-dark-aqua/20 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-gruvbox-dark-aqua" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gruvbox-dark-fg0 mb-2">Events</h3>
                        <p class="text-sm text-gruvbox-dark-fg3">Stay updated with upcoming college events and request to create your own events.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gruvbox-dark-bg3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gruvbox-dark-fg3">
                    &copy; {{ date('Y') }} CSI-VESIT Platform. Built for VESIT students.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
