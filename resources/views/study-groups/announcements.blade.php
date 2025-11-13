<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('study-groups.show', $studyGroup) }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
                {{ $studyGroup->name }}
            </a>
            <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">/</span>
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                Announcements
            </h2>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 border border-gruvbox-light-green dark:border-gruvbox-dark-green">
            <p class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ session('success') }}</p>
        </div>
    @endif

    @include('study-groups.partials.navigation-tabs')

    @if($studyGroup->isModerator(auth()->user()))
        <div class="mb-6">
            <button onclick="document.getElementById('createAnnouncementModal').classList.remove('hidden')"
                    class="px-6 py-3 bg-gruvbox-light-orange dark:bg-gruvbox-dark-orange text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Announcement
            </button>
        </div>
    @endif

    <!-- Announcements List -->
    <div class="space-y-4">
        @forelse($announcements as $announcement)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border-l-4 border-gruvbox-light-orange dark:border-gruvbox-dark-orange">
                <div class="flex items-start space-x-4">
                    <div class="p-3 rounded-lg bg-gruvbox-light-orange/20 dark:bg-gruvbox-dark-orange/20">
                        <svg class="w-6 h-6 text-gruvbox-light-orange dark:text-gruvbox-dark-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $announcement->title }}</h3>
                                <div class="flex items-center text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mt-1">
                                    <span class="font-medium">{{ $announcement->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            @if($studyGroup->isModerator(auth()->user()) && $announcement->user_id === auth()->id())
                                <form action="{{ route('study-groups.announcements.destroy', [$studyGroup, $announcement]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gruvbox-light-red dark:text-gruvbox-dark-red hover:opacity-75">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 whitespace-pre-wrap">{{ $announcement->content }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1">
                <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">No announcements yet.</p>
                @if($studyGroup->isModerator(auth()->user()))
                    <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mt-2">Post the first announcement to keep everyone informed!</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Create Announcement Modal -->
    @if($studyGroup->isModerator(auth()->user()))
        <div id="createAnnouncementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">New Announcement</h3>

                <form action="{{ route('study-groups.announcements.store', $studyGroup) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Title</label>
                            <input type="text" name="title" required
                                   class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Content</label>
                            <textarea name="content" rows="6" required
                                      class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-gruvbox-light-orange dark:bg-gruvbox-dark-orange text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                            Post Announcement
                        </button>
                        <button type="button" onclick="document.getElementById('createAnnouncementModal').classList.add('hidden')"
                                class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
