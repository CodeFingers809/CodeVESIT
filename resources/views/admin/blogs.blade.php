<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Manage Blogs
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Admin Dashboard
        </a>
    </div>

    <div class="space-y-4">
        @forelse($blogs as $blog)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $blog->title }}</h3>
                            <span class="px-2 py-1 rounded text-xs {{ $blog->is_published ? 'bg-gruvbox-light-green/20 text-gruvbox-light-green' : 'bg-gruvbox-light-red/20 text-gruvbox-light-red' }}">
                                {{ $blog->is_published ? 'Published' : 'Unpublished' }}
                            </span>
                        </div>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-2">By {{ $blog->user->name }} • {{ $blog->views }} views</p>
                        <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">Published: {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('admin.blogs.toggle', $blog) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 {{ $blog->is_published ? 'bg-gruvbox-light-red' : 'bg-gruvbox-light-green' }} dark:{{ $blog->is_published ? 'bg-gruvbox-dark-red' : 'bg-gruvbox-dark-green' }} text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                                {{ $blog->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                        <a href="{{ route('blogs.show', $blog) }}" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                            View
                        </a>
                        <form action="{{ route('admin.blogs.delete', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this blog? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No blogs found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $blogs->links() }}
    </div>
</x-app-layout>
