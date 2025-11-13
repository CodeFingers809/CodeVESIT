<!-- Channel Tabs -->
<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('study-groups.todos', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.todos*') ? 'bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
        Todos
    </a>
    <a href="{{ route('study-groups.announcements', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.announcements*') ? 'bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
        Announcements
    </a>
    <a href="{{ route('study-groups.chat', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.chat*') ? 'bg-gruvbox-light-purple dark:bg-gruvbox-dark-purple text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
        Chat
    </a>
    <a href="{{ route('study-groups.calendar', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.calendar*') ? 'bg-gruvbox-light-orange dark:bg-gruvbox-dark-orange text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
        Calendar
    </a>
    <a href="{{ route('study-groups.settings', $studyGroup) }}" class="px-4 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('study-groups.settings*') ? 'bg-gruvbox-light-aqua dark:bg-gruvbox-dark-aqua text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0' : 'bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 hover:bg-gruvbox-light-bg2 dark:hover:bg-gruvbox-dark-bg2' }}">
        Settings
    </a>
</div>
