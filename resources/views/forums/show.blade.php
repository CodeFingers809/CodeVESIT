<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                {{ $forum->name }}
            </h2>
            <button onclick="document.getElementById('createPostModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                New Post
            </button>
        </div>
    </x-slot>

    <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
        <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">{{ $forum->description }}</p>
    </div>

    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex items-start space-x-4">
                    <img src="{{ $post->user->getAvatarUrl() }}" alt="{{ $post->user->name }}" class="w-12 h-12 rounded-full">

                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-lg text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                                    <a href="{{ route('forums.posts.show', [$forum, $post]) }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                                    <span class="font-medium">{{ $post->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            @if(auth()->id() !== $post->user_id)
                                <button onclick="openReportModal('post', {{ $post->id }})"
                                        class="text-gruvbox-light-red dark:text-gruvbox-dark-red hover:opacity-75">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-3">{{ Str::limit($post->content, 300) }}</p>

                        <div class="flex items-center space-x-4 text-sm">
                            <a href="{{ route('forums.posts.show', [$forum, $post]) }}"
                               class="flex items-center text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                {{ $post->comments_count }} comments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1">
                <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-4">No posts yet. Be the first to start a discussion!</p>
                <button onclick="document.getElementById('createPostModal').classList.remove('hidden')"
                        class="px-6 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    Create First Post
                </button>
            </div>
        @endforelse
    </div>

    @if($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif

    <!-- Create Post Modal -->
    <div id="createPostModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Create New Post</h3>

            <form action="{{ route('forums.posts.store', $forum) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Title</label>
                        <input type="text" name="title" required
                               class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Content</label>
                        <textarea name="content" rows="8" required
                                  class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Create Post
                    </button>
                    <button type="button" onclick="document.getElementById('createPostModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Modal -->
    <div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Report Content</h3>

            <form id="reportForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Reason</label>
                        <textarea name="reason" rows="4" required
                                  placeholder="Please describe why you're reporting this content..."
                                  class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Submit Report
                    </button>
                    <button type="button" onclick="document.getElementById('reportModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReportModal(type, id) {
            const form = document.getElementById('reportForm');
            if (type === 'post') {
                form.action = `/forums/{{ $forum->id }}/posts/${id}/report`;
            } else {
                form.action = `/forums/{{ $forum->id }}/posts/0/comments/${id}/report`;
            }
            document.getElementById('reportModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
