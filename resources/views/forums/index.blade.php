<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Forums
        </h2>
    </x-slot>

    <div class="mb-6 p-6 rounded-lg bg-gradient-to-r from-gruvbox-light-green/80 to-gruvbox-light-aqua/80 dark:from-gruvbox-dark-green/80 dark:to-gruvbox-dark-aqua/80">
        <h3 class="text-2xl font-bold text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0">College-Wide Forums</h3>
        <p class="mt-2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Engage in discussions with students across all departments</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($forums as $forum)
            <a href="{{ route('forums.show', $forum) }}"
               class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-blue dark:hover:border-gruvbox-dark-blue transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $forum->name }}</h3>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gruvbox-light-blue/20 dark:bg-gruvbox-dark-blue/20 text-gruvbox-light-blue dark:text-gruvbox-dark-blue">
                        {{ $forum->posts_count }} posts
                    </span>
                </div>

                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4">{{ $forum->description }}</p>

                @if($forum->latest_post)
                    <div class="flex items-center text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Last post {{ $forum->latest_post->created_at->diffForHumans() }}
                    </div>
                @else
                    <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">No posts yet. Be the first to post!</p>
                @endif
            </a>
        @empty
            <div class="col-span-2 text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">No forums available yet.</p>
                @if(auth()->user()->isAdmin())
                    <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mt-2">Go to Admin Panel to create forums.</p>
                @endif
            </div>
        @endforelse
    </div>
</x-app-layout>
