<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                Study Groups
            </h2>
            <a href="{{ route('study-groups.create') }}" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-medium">
                Request New Group
            </a>
        </div>
    </x-slot>

    <!-- Join with Code -->
    <div class="mb-6 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
        <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Join Study Group</h3>
        <form method="POST" action="{{ route('study-groups.join') }}" class="flex gap-3">
            @csrf
            <input type="text" name="join_code" placeholder="Enter 8-character join code" maxlength="8" class="flex-1 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:border-gruvbox-light-blue dark:focus:border-gruvbox-dark-blue focus:ring focus:ring-gruvbox-light-blue/20 dark:focus:ring-gruvbox-dark-blue/20" required>
            <button type="submit" class="px-6 py-2 bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-medium">
                Join
            </button>
        </form>
        <p class="mt-2 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Ask your CR or moderator for the join code</p>
    </div>

    <!-- My Study Groups -->
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">My Study Groups</h3>
        @if($myGroups->isEmpty())
            <div class="p-8 text-center rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg4 dark:text-gruvbox-dark-fg4 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">You haven't joined any study groups yet</p>
                <p class="mt-2 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Use a join code or request to create a new group</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($myGroups as $group)
                    <a href="{{ route('study-groups.show', $group) }}" class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-blue dark:hover:border-gruvbox-dark-blue transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <h4 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $group->name }}</h4>
                            @if($group->isModerator(auth()->user()))
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow">
                                    Moderator
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 line-clamp-2 mb-4">{{ $group->description }}</p>
                        <div class="flex items-center text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ $group->members()->count() }} members
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Available Study Groups -->
    <div>
        <h3 class="text-xl font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Available Study Groups</h3>
        @if($availableGroups->isEmpty())
            <div class="p-8 text-center rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">No other study groups available</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availableGroups as $group)
                    <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                        <h4 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-3">{{ $group->name }}</h4>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 line-clamp-2 mb-4">{{ $group->description }}</p>
                        <div class="flex items-center justify-between text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ $group->members()->count() }} members
                            </span>
                            <span class="px-2 py-1 bg-gruvbox-light-aqua/20 dark:bg-gruvbox-dark-aqua/20 text-gruvbox-light-aqua dark:text-gruvbox-dark-aqua rounded font-medium">
                                Need code
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
