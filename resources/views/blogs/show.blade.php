<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('blogs.index') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
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
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="prose prose-lg max-w-none">
                <div class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 whitespace-pre-wrap leading-relaxed">
                    {{ $blog->content }}
                </div>
            </div>
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
