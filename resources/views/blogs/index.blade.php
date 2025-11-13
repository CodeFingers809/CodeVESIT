<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                Blogs
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('blogs.my') }}"
                   class="px-4 py-2 bg-gruvbox-light-purple dark:bg-gruvbox-dark-purple text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    My Blogs
                </a>
                <a href="{{ route('blogs.create') }}"
                   class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    Write Blog
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mb-6 p-6 rounded-lg bg-gradient-to-r from-gruvbox-light-purple/80 to-gruvbox-light-blue/80 dark:from-gruvbox-dark-purple/80 dark:to-gruvbox-dark-blue/80">
        <h3 class="text-2xl font-bold text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0">College Blogs</h3>
        <p class="mt-2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Share your knowledge and experiences with the college community</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($blogs as $blog)
            <a href="{{ route('blogs.show', $blog) }}"
               class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-purple dark:hover:border-gruvbox-dark-purple transition-colors">
                <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">{{ $blog->title }}</h3>

                @if($blog->excerpt)
                    <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4 line-clamp-3">{{ $blog->excerpt }}</p>
                @else
                    <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4 line-clamp-3">{{ Str::limit(strip_tags($blog->content), 150) }}</p>
                @endif

                <div class="flex items-center space-x-3 mb-3">
                    <img src="{{ $blog->user->getAvatarUrl() }}" alt="{{ $blog->user->name }}" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $blog->user->name }}</p>
                        <p class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">{{ $blog->published_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $blog->views }}
                        </span>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 text-gruvbox-light-green dark:text-gruvbox-dark-green">
                        Published
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4">No blogs published yet.</p>
                <a href="{{ route('blogs.create') }}"
                   class="inline-block px-6 py-2 bg-gruvbox-light-purple dark:bg-gruvbox-dark-purple text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    Write First Blog
                </a>
            </div>
        @endforelse
    </div>

    @if($blogs->hasPages())
        <div class="mt-6">
            {{ $blogs->links() }}
        </div>
    @endif
</x-app-layout>
