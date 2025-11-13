<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Dashboard
        </h2>
    </x-slot>

    <!-- Welcome Banner -->
    <div class="mb-6 p-6 rounded-lg bg-gradient-to-r from-gruvbox-light-orange/80 to-gruvbox-light-yellow/80 dark:from-gruvbox-dark-orange/80 dark:to-gruvbox-dark-yellow/80">
        <h3 class="text-2xl font-bold text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0">Welcome back, {{ $user->name }}!</h3>
        <p class="mt-2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $user->department }} - {{ $user->year }} {{ $user->division }}</p>
        @if($user->isAdmin())
            <span class="inline-block mt-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 px-3 py-1 rounded-full text-sm font-semibold">Admin</span>
        @elseif($user->isModerator())
            <span class="inline-block mt-2 bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 px-3 py-1 rounded-full text-sm font-semibold">Moderator</span>
        @endif>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Study Groups -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Study Groups</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ auth()->user()->studyGroups()->count() }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-blue/20 dark:bg-gruvbox-dark-blue/20">
                    <svg class="w-8 h-8 text-gruvbox-light-blue dark:text-gruvbox-dark-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Blogs -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">My Blogs</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ auth()->user()->blogs()->count() }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-purple/20 dark:bg-gruvbox-dark-purple/20">
                    <svg class="w-8 h-8 text-gruvbox-light-purple dark:text-gruvbox-dark-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Calendar Events -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">My Tasks</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ auth()->user()->calendarEvents()->where('is_completed', false)->count() }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20">
                    <svg class="w-8 h-8 text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Forum Posts -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Forum Posts</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ auth()->user()->forumPosts()->count() }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20">
                    <svg class="w-8 h-8 text-gruvbox-light-green dark:text-gruvbox-dark-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('study-groups.create') }}" class="flex items-center justify-between p-3 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors">
                    <span class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 font-medium">Create Study Group</span>
                    <svg class="w-5 h-5 text-gruvbox-light-blue dark:text-gruvbox-dark-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
                <a href="{{ route('blogs.create') }}" class="flex items-center justify-between p-3 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors">
                    <span class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 font-medium">Write a Blog</span>
                    <svg class="w-5 h-5 text-gruvbox-light-purple dark:text-gruvbox-dark-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
                <a href="{{ route('events.create') }}" class="flex items-center justify-between p-3 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors">
                    <span class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 font-medium">Request Event</span>
                    <svg class="w-5 h-5 text-gruvbox-light-aqua dark:text-gruvbox-dark-aqua" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Platform Info -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Platform Features</h3>
            <div class="space-y-3 text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-gruvbox-light-green dark:text-gruvbox-dark-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Study groups with todos, announcements, and chat</span>
                </div>
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-gruvbox-light-green dark:text-gruvbox-dark-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>College-wide forums for discussions</span>
                </div>
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-gruvbox-light-green dark:text-gruvbox-dark-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Write and publish blogs with moderation</span>
                </div>
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-gruvbox-light-green dark:text-gruvbox-dark-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Events calendar and personal task management</span>
                </div>
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-gruvbox-light-green dark:text-gruvbox-dark-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Report system for content moderation</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
