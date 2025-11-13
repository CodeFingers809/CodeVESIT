<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('study-groups.show', $studyGroup) }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
                {{ $studyGroup->name }}
            </a>
            <span class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">/</span>
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                Chat
            </h2>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 border border-gruvbox-light-green dark:border-gruvbox-dark-green">
            <p class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ session('success') }}</p>
        </div>
    @endif

    @include('study-groups.partials.navigation-tabs')

    <div class="flex flex-col h-[calc(100vh-300px)]">
        <!-- Messages Container -->
        <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 rounded-t-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 space-y-4">
            @forelse($messages as $message)
                <div class="flex items-start space-x-3">
                    <img src="{{ $message->user->getAvatarUrl() }}" alt="{{ $message->user->name }}" class="w-10 h-10 rounded-full flex-shrink-0">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline space-x-2 mb-1">
                            <span class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $message->user->name }}</span>
                            <span class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">{{ $message->created_at->format('h:i A') }}</span>
                        </div>

                        <div class="flex items-start justify-between">
                            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 whitespace-pre-wrap break-words">{{ $message->content }}</p>

                            @if(auth()->id() !== $message->user_id)
                                <button onclick="openReportModal({{ $message->id }})"
                                        class="ml-2 text-gruvbox-light-red dark:text-gruvbox-dark-red hover:opacity-75 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="p-4 rounded-b-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border-x border-b border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <form action="{{ route('study-groups.chat.store', $studyGroup) }}" method="POST" class="flex gap-3">
                @csrf
                <textarea name="message" rows="1" required
                          placeholder="Type your message..."
                          class="flex-1 px-3 py-2 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 resize-none"
                          onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>
                <button type="submit"
                        class="px-6 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                    Send
                </button>
            </form>
            <p class="text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mt-2">Press Enter to send, Shift+Enter for new line</p>
        </div>
    </div>

    <!-- Report Modal -->
    <div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Report Message</h3>

            <form id="reportForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Reason</label>
                        <textarea name="reason" rows="4" required
                                  placeholder="Please describe why you're reporting this message..."
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
        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
        });

        function openReportModal(messageId) {
            const form = document.getElementById('reportForm');
            form.action = `/study-groups/{{ $studyGroup->id }}/chat/${messageId}/report`;
            document.getElementById('reportModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
