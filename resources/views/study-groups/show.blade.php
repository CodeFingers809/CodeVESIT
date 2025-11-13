<x-app-layout>
    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">Dashboard</a></li>
        <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
        <li><a href="{{ route('study-groups.index') }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">Study Groups</a></li>
        <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
        <li class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 font-medium">{{ $studyGroup->name }}</li>
    </x-slot>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                {{ $studyGroup->name }}
            </h2>
            <p class="mt-1 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                {{ $studyGroup->members()->count() }} members
                @if($isModerator)
                    • Moderator
                @endif
            </p>
        </div>
    </x-slot>

    <!-- Join Code (Moderators Only) -->
    @if($isModerator)
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 border border-gruvbox-light-yellow dark:border-gruvbox-dark-yellow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Join Code (Share with classmates)</p>
                    <p class="mt-1 text-2xl font-bold font-mono text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow">{{ $studyGroup->join_code }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $studyGroup->join_code }}')" class="px-4 py-2 bg-gruvbox-light-yellow dark:bg-gruvbox-dark-yellow text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                    Copy
                </button>
            </div>
        </div>
    @endif

    <!-- Channel Tabs -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('study-groups.todos', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.todos') ? 'bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
            Todos
        </a>
        <a href="{{ route('study-groups.announcements', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.announcements') ? 'bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
            Announcements
        </a>
        <a href="{{ route('study-groups.chat', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.chat') ? 'bg-gruvbox-light-purple dark:bg-gruvbox-dark-purple text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
            Chat
        </a>
        <a href="{{ route('study-groups.calendar', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.calendar*') ? 'bg-gruvbox-light-orange dark:bg-gruvbox-dark-orange text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
            Calendar
        </a>
        <a href="{{ route('study-groups.settings', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.settings*') ? 'bg-gruvbox-light-aqua dark:bg-gruvbox-dark-aqua text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
            Settings
        </a>
    </div>

    <!-- Group Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Description -->
        <div class="md:col-span-2 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-3">About</h3>
            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">{{ $studyGroup->description ?? 'No description provided.' }}</p>
        </div>

        <!-- Quick Stats -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Stats</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Members</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $studyGroup->members()->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Todos</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $studyGroup->todos()->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Announcements</span>
                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $studyGroup->announcements()->count() }}</span>
                </div>
            </div>

            @if(!$studyGroup->isModerator(auth()->user()) && $studyGroup->created_by !== auth()->id())
                <form method="POST" action="{{ route('study-groups.leave', $studyGroup) }}" class="mt-6">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you sure you want to leave this group?')" class="w-full px-4 py-2 bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20 text-gruvbox-light-red dark:text-gruvbox-dark-red rounded-lg hover:bg-gruvbox-light-red/30 dark:hover:bg-gruvbox-dark-red/30 transition-colors font-medium">
                        Leave Group
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
