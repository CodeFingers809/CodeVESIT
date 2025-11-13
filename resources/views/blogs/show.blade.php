<x-app-layout>
    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">Dashboard</a></li>
        <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
        <li><a href="{{ route('blogs.index') }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">Blogs</a></li>
        <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
        <li class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 font-medium">{{ Str::limit($blog->title, 40) }}</li>
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('blogs.index') }}" class="text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue hover:underline">
                ← Back to Blogs
            </a>
        </div>
    </x-slot>

    <article class="max-w-4xl mx-auto">
        <!-- Blog Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">{{ $blog->title }}</h1>

            @if($blog->excerpt)
                <p class="text-xl text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-6">{{ $blog->excerpt }}</p>
            @endif

            <div class="flex items-center space-x-4 mb-6">
                <img src="{{ $blog->user->getAvatarUrl() }}" alt="{{ $blog->user->name }}" class="w-12 h-12 rounded-full">
                <div>
                    <p class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $blog->user->name }}</p>
                    <div class="flex items-center text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                        <span>{{ $blog->published_at->format('M d, Y') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $blog->views }} views</span>
                    </div>
                </div>
            </div>

            <div class="border-b border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3"></div>
        </div>

        <!-- Blog Content -->
        <div class="p-8 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            @if($parsedContent)
                <div class="prose prose-lg max-w-none">
                    <div class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 leading-relaxed text-base">
                        {!! $parsedContent !!}
                    </div>
                </div>
                @if($blog->document_path)
                    <div class="mt-8 pt-6 border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                        <a href="{{ $blog->document_path }}" download class="inline-flex items-center px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Original Document
                        </a>
                    </div>
                @endif
            @elseif($blog->content)
                <div class="prose prose-lg max-w-none">
                    <div class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 whitespace-pre-wrap leading-relaxed">
                        {{ $blog->content }}
                    </div>
                </div>
            @elseif($blog->document_path)
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gruvbox-light-blue dark:text-gruvbox-dark-blue mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4">This blog is available as a document</p>
                    <a href="{{ $blog->document_path }}" download class="inline-flex items-center px-6 py-3 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Document
                    </a>
                </div>
            @else
                <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No content available</p>
            @endif
        </div>

        <!-- Author Info -->
        <div class="mt-8 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-lg font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">About the Author</h3>
            <div class="flex items-start space-x-4">
                <img src="{{ $blog->user->getAvatarUrl() }}" alt="{{ $blog->user->name }}" class="w-16 h-16 rounded-full">
                <div>
                    <p class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $blog->user->name }}</p>
                    <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                        {{ $blog->user->department }} - {{ $blog->user->year }} {{ $blog->user->division }}
                    </p>
                    @if($blog->user->bio)
                        <p class="mt-2 text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">{{ $blog->user->bio }}</p>
                    @endif
                </div>
            </div>
        </div>
    </article>
</x-app-layout>
