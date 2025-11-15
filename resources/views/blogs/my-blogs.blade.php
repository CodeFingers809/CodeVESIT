<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('blogs.index') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:opacity-80 transition-opacity" title="Back to Blogs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    My Blogs
                </h2>
            </div>
            <a href="{{ route('blogs.create') }}"
               class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                Write New Blog
            </a>
        </div>
    </x-slot>

    <!-- Pending Requests -->
    @if($requests->count() > 0)
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Pending Review</h3>
            <div class="space-y-4">
                @foreach($requests as $request)
                    <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $request->title }}</h4>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($request->status === 'pending') bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow
                                @elseif($request->status === 'approved') bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 text-gruvbox-light-green dark:text-gruvbox-dark-green
                                @else bg-gruvbox-light-red/20 dark:bg-gruvbox-dark-red/20 text-gruvbox-light-red dark:text-gruvbox-dark-red
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>

                        @if($request->excerpt)
                            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-3">{{ $request->excerpt }}</p>
                        @endif

                        <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            Submitted {{ $request->created_at->diffForHumans() }}
                        </p>

                        @if($request->status === 'rejected' && $request->rejection_reason)
                            <div class="mt-3 p-3 rounded bg-gruvbox-light-red/10 dark:bg-gruvbox-dark-red/10 border border-gruvbox-light-red/30 dark:border-gruvbox-dark-red/30">
                                <p class="text-sm font-semibold text-gruvbox-light-red dark:text-gruvbox-dark-red mb-1">Rejection Reason:</p>
                                <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">{{ $request->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Published Blogs -->
    <div>
        <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Published Blogs</h3>

        @if($blogs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($blogs as $blog)
                    <a href="{{ route('blogs.show', $blog) }}"
                       class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-purple dark:hover:border-gruvbox-dark-purple transition-colors">
                        <h4 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">{{ $blog->title }}</h4>

                        @if($blog->excerpt)
                            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-3 line-clamp-2">{{ $blog->excerpt }}</p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            <span>{{ $blog->published_at->format('M d, Y') }}</span>
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $blog->views }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1">
                <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4">You haven't published any blogs yet.</p>
                <a href="{{ route('blogs.create') }}"
                   class="inline-block px-6 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    Write Your First Blog
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
