<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Admin Panel
        </h2>
    </x-slot>

    <!-- Admin Welcome -->
    <div class="mb-6 p-6 rounded-lg bg-gradient-to-r from-gruvbox-light-red/80 to-gruvbox-light-orange/80 dark:from-gruvbox-dark-red/80 dark:to-gruvbox-dark-orange/80">
        <h3 class="text-2xl font-bold text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0">Admin Dashboard</h3>
        <p class="mt-2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Manage platform content and user requests</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Pending Study Groups</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $pendingStudyGroups }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-blue/20 dark:bg-gruvbox-dark-blue/20">
                    <svg class="w-8 h-8 text-gruvbox-light-blue dark:text-gruvbox-dark-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Pending Blogs</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $pendingBlogs }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-purple/20 dark:bg-gruvbox-dark-purple/20">
                    <svg class="w-8 h-8 text-gruvbox-light-purple dark:text-gruvbox-dark-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Pending Events</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $pendingEvents }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-aqua/20 dark:bg-gruvbox-dark-aqua/20">
                    <svg class="w-8 h-8 text-gruvbox-light-aqua dark:text-gruvbox-dark-aqua" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Pending Reports</p>
                    <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $pendingReports }}</p>
                </div>
                <div class="p-3 rounded-lg bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20">
                    <svg class="w-8 h-8 text-gruvbox-light-red dark:text-gruvbox-dark-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <!-- Management Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.study-groups') }}" class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-blue dark:hover:border-gruvbox-dark-blue transition-colors">
            <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Manage Study Groups</h4>
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Approve or reject study group requests</p>
        </a>

        <a href="{{ route('admin.blog-requests') }}" class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-purple dark:hover:border-gruvbox-dark-purple transition-colors">
            <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Manage Blog Requests</h4>
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Review and approve blog submissions</p>
        </a>

        <a href="{{ route('admin.event-requests') }}" class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-aqua dark:hover:border-gruvbox-dark-aqua transition-colors">
            <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Manage Event Requests</h4>
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Review and approve event submissions</p>
        </a>

        <a href="{{ route('admin.reports') }}" class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-red dark:hover:border-gruvbox-dark-red transition-colors">
            <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Manage Reports</h4>
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Handle reported content and users</p>
        </a>

        <a href="{{ route('admin.users') }}" class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-green dark:hover:border-gruvbox-dark-green transition-colors">
            <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Manage Users</h4>
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Manage user roles and status</p>
        </a>

        <a href="{{ route('admin.database') }}" class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-orange dark:hover:border-gruvbox-dark-orange transition-colors">
            <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Database Overview</h4>
            <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">View database statistics</p>
        </a>
    </div>
</x-app-layout>
