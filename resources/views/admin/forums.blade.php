<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                Manage Forums
            </h2>
            <button onclick="document.getElementById('createForumModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                Create Forum
            </button>
        </div>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Admin Dashboard
        </a>
    </div>

    <div class="space-y-4">
        @forelse($forums as $forum)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">{{ $forum->name }}</h3>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-2">{{ $forum->description }}</p>
                        <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">{{ $forum->posts_count }} posts</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openEditModal({{ json_encode($forum) }})"
                                class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                            Edit
                        </button>
                        <a href="{{ route('forums.show', $forum) }}" class="px-4 py-2 bg-gruvbox-light-aqua dark:bg-gruvbox-dark-aqua text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                            View
                        </a>
                        <form action="{{ route('admin.forums.delete', $forum) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this forum and all its posts? This action cannot be undone.')">
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
            <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No forums found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $forums->links() }}
    </div>

    <!-- Create Forum Modal -->
    <div id="createForumModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Create New Forum</h3>

            <form action="{{ route('admin.forums.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Name</label>
                        <input type="text" name="name" required
                               class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Description</label>
                        <textarea name="description" rows="3" required
                                  class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Create Forum
                    </button>
                    <button type="button" onclick="document.getElementById('createForumModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Forum Modal -->
    <div id="editForumModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Edit Forum</h3>

            <form id="editForumForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Name</label>
                        <input type="text" name="name" id="edit_name" required
                               class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3" required
                                  class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Update Forum
                    </button>
                    <button type="button" onclick="document.getElementById('editForumModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(forum) {
            document.getElementById('editForumForm').action = `/admin/forums/${forum.id}`;
            document.getElementById('edit_name').value = forum.name;
            document.getElementById('edit_description').value = forum.description;
            document.getElementById('editForumModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
