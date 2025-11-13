<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Manage Study Groups
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
        @forelse($studyGroups as $group)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $group->name }}</h3>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mt-1">{{ $group->description }}</p>
                        <div class="mt-3 flex items-center gap-4 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            <span>Created by: {{ $group->creator->name }}</span>
                            <span>Members: {{ $group->members()->count() }}</span>
                            <span>Status: <span class="px-2 py-1 rounded text-xs {{ $group->status === 'approved' ? 'bg-gruvbox-light-green/20 text-gruvbox-light-green' : ($group->status === 'rejected' ? 'bg-gruvbox-light-red/20 text-gruvbox-light-red' : 'bg-gruvbox-light-yellow/20 text-gruvbox-light-yellow') }}">{{ ucfirst($group->status) }}</span></span>
                        </div>
                    </div>
                    @if($group->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.study-groups.approve', $group) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.study-groups.reject', $group) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                                    Reject
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No study groups found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $studyGroups->links() }}
    </div>
</x-app-layout>
