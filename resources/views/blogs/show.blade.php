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
            @if($blog->content)
                <div class="blog-content prose prose-lg max-w-none text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 leading-relaxed">
                    {!! $blog->content !!}
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

    <!-- Gruvbox Styling for Blog Content -->
    <style>
        .blog-content h1 {
            font-size: 2em;
            font-weight: 700;
            margin-top: 1.5em;
            margin-bottom: 0.75em;
            color: #fb4934; /* Gruvbox red */
            line-height: 1.2;
        }

        .blog-content h2 {
            font-size: 1.75em;
            font-weight: 600;
            margin-top: 1.25em;
            margin-bottom: 0.65em;
            color: #fabd2f; /* Gruvbox yellow */
            line-height: 1.3;
        }

        .blog-content h3 {
            font-size: 1.5em;
            font-weight: 600;
            margin-top: 1em;
            margin-bottom: 0.5em;
            color: #b8bb26; /* Gruvbox green */
            line-height: 1.3;
        }

        .blog-content h4 {
            font-size: 1.25em;
            font-weight: 600;
            margin-top: 1em;
            margin-bottom: 0.5em;
            color: #83a598; /* Gruvbox blue */
            line-height: 1.4;
        }

        .blog-content h5,
        .blog-content h6 {
            font-size: 1.1em;
            font-weight: 600;
            margin-top: 0.75em;
            margin-bottom: 0.5em;
            color: #d3869b; /* Gruvbox purple */
            line-height: 1.4;
        }

        .blog-content p {
            margin-bottom: 1em;
            line-height: 1.7;
        }

        .blog-content strong,
        .blog-content b {
            font-weight: 700;
            color: #fe8019; /* Gruvbox orange */
        }

        .blog-content em,
        .blog-content i {
            font-style: italic;
            color: #8ec07c; /* Gruvbox aqua */
        }

        .blog-content ul,
        .blog-content ol {
            margin-left: 1.5em;
            margin-bottom: 1em;
        }

        .blog-content li {
            margin-bottom: 0.5em;
            line-height: 1.6;
        }

        .blog-content ul {
            list-style-type: disc;
        }

        .blog-content ol {
            list-style-type: decimal;
        }

        .blog-content blockquote {
            border-left: 4px solid #b8bb26; /* Gruvbox green */
            padding-left: 1em;
            margin: 1em 0;
            font-style: italic;
            opacity: 0.9;
        }

        .blog-content code {
            background-color: rgba(235, 219, 178, 0.1);
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-family: monospace;
            font-size: 0.9em;
            color: #fe8019; /* Gruvbox orange */
        }

        .blog-content pre {
            background-color: rgba(40, 40, 40, 0.3);
            padding: 1em;
            border-radius: 5px;
            overflow-x: auto;
            margin: 1em 0;
        }

        .blog-content pre code {
            background-color: transparent;
            padding: 0;
        }

        .blog-content a {
            color: #83a598; /* Gruvbox blue */
            text-decoration: underline;
        }

        .blog-content a:hover {
            color: #458588; /* Gruvbox blue dark */
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
            margin: 1em 0;
            border-radius: 5px;
        }

        .blog-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5em 0;
        }

        .blog-content th {
            background-color: rgba(251, 73, 52, 0.1); /* Gruvbox red with opacity */
            font-weight: 600;
            padding: 0.75em;
            border: 1px solid rgba(235, 219, 178, 0.2);
            text-align: left;
        }

        .blog-content td {
            padding: 0.75em;
            border: 1px solid rgba(235, 219, 178, 0.2);
        }

        .blog-content tr:nth-child(even) {
            background-color: rgba(235, 219, 178, 0.05);
        }
    </style>
</x-app-layout>
