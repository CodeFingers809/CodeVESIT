<x-app-layout>
    <x-slot name="breadcrumbs">
        <li><a href="{{ route('dashboard') }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">Dashboard</a></li>
        <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
        <li><a href="{{ route('forums.index') }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">Forums</a></li>
        <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
        <li><a href="{{ route('forums.show', $forum) }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">{{ $forum->name }}</a></li>
        <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
        <li class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 font-medium">{{ Str::limit($post->title, 30) }}</li>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            {{ $post->title }}
        </h2>
    </x-slot>

    <!-- Post Content -->
    <div class="mb-6 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
        <div class="flex items-start space-x-4 mb-4">
            <img src="{{ $post->user->getAvatarUrl() }}" alt="{{ $post->user->name }}" class="w-16 h-16 rounded-full">

            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $post->user->name }}</h3>
                        <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            {{ $post->user->department }} - {{ $post->user->year }} {{ $post->user->division }}
                        </p>
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
                <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mt-2">
                    Posted {{ $post->created_at->diffForHumans() }}
                </p>
            </div>
        </div>

        <div class="prose prose-gruvbox max-w-none">
            <p class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 whitespace-pre-wrap">{{ $post->content }}</p>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">
            Comments ({{ $post->comments()->whereNull('parent_id')->count() }})
        </h3>

        <!-- Add Comment Form -->
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <form action="{{ route('forums.posts.comments.store', [$forum, $post]) }}" method="POST">
                @csrf
                <textarea name="content" rows="3" required
                          placeholder="Add a comment..."
                          class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-3"></textarea>
                <button type="submit"
                        class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    Post Comment
                </button>
            </form>
        </div>

        <!-- Comments List -->
        <div class="space-y-4">
            @forelse($post->comments()->whereNull('parent_id')->with('user', 'replies.user')->latest()->get() as $comment)
                <div class="p-4 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                    <div class="flex items-start space-x-3">
                        <img src="{{ $comment->user->getAvatarUrl() }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full">

                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <div>
                                    <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $comment->user->name }}</span>
                                    <span class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 ml-2">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                @if(auth()->id() !== $comment->user_id)
                                    <button onclick="openReportModal('comment', {{ $comment->id }})"
                                            class="text-gruvbox-light-red dark:text-gruvbox-dark-red hover:opacity-75">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 whitespace-pre-wrap">{{ $comment->content }}</p>

                            <button onclick="toggleReplyForm({{ $comment->id }})"
                                    class="mt-2 text-sm text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
                                Reply
                            </button>

                            <!-- Reply Form -->
                            <div id="replyForm{{ $comment->id }}" class="hidden mt-3">
                                <form action="{{ route('forums.posts.comments.store', [$forum, $post]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <textarea name="content" rows="2" required
                                              placeholder="Write a reply..."
                                              class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2"></textarea>
                                    <div class="flex gap-2">
                                        <button type="submit"
                                                class="px-3 py-1 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded text-sm hover:opacity-90 transition-opacity">
                                            Reply
                                        </button>
                                        <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                                                class="px-3 py-1 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded text-sm hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                                <div class="mt-4 space-y-3 pl-4 border-l-2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                                    @foreach($comment->replies as $reply)
                                        <div class="flex items-start space-x-3">
                                            <img src="{{ $reply->user->getAvatarUrl() }}" alt="{{ $reply->user->name }}" class="w-8 h-8 rounded-full">

                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <div>
                                                        <span class="font-semibold text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $reply->user->name }}</span>
                                                        <span class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 ml-2">
                                                            {{ $reply->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>

                                                    @if(auth()->id() !== $reply->user_id)
                                                        <button onclick="openReportModal('comment', {{ $reply->id }})"
                                                                class="text-gruvbox-light-red dark:text-gruvbox-dark-red hover:opacity-75">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>

                                                <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 whitespace-pre-wrap">{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 p-4 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1">
                    <p class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">No comments yet. Be the first to comment!</p>
                </div>
            @endforelse
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
        function toggleReplyForm(commentId) {
            const form = document.getElementById('replyForm' + commentId);
            form.classList.toggle('hidden');
        }

        function openReportModal(type, id) {
            const form = document.getElementById('reportForm');
            if (type === 'post') {
                form.action = '{{ route('forums.posts.report', [$forum, $post]) }}';
            } else {
                form.action = `/forums/{{ $forum->id }}/posts/{{ $post->id }}/comments/${id}/report`;
            }
            document.getElementById('reportModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
