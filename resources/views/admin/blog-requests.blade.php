<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Manage Blog Requests
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Admin Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 border border-gruvbox-light-green dark:border-gruvbox-dark-green">
            <p class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ session('success') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        @forelse($blogRequests as $request)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $request->title }}</h3>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mt-1">{{ $request->excerpt }}</p>
                        <div class="mt-3 flex items-center gap-4 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            <span>Author: {{ $request->user->name }}</span>
                            <span>Status: <span class="px-2 py-1 rounded text-xs {{ $request->status === 'approved' ? 'bg-gruvbox-light-green/20 text-gruvbox-light-green' : ($request->status === 'rejected' ? 'bg-gruvbox-light-red/20 text-gruvbox-light-red' : 'bg-gruvbox-light-yellow/20 text-gruvbox-light-yellow') }}">{{ ucfirst($request->status) }}</span></span>
                        </div>
                        @if($request->rejection_reason)
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">Rejection reason: {{ $request->rejection_reason }}</p>
                        @endif
                    </div>
                    @if($request->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.blog-requests.approve', $request) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                                    Approve
                                </button>
                            </form>
                            <button onclick="openRejectModal({{ $request->id }})" class="px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                                Reject
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No blog requests found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $blogRequests->links() }}
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Reject Blog Request</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Rejection Reason</label>
                    <textarea name="rejection_reason" required rows="4" class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                        Reject
                    </button>
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(requestId) {
            document.getElementById('rejectForm').action = `/admin/blog-requests/${requestId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
