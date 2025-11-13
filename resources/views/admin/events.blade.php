<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Manage Events
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Admin Dashboard
        </a>
    </div>

    <div class="space-y-4">
        @forelse($events as $event)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $event->title }}</h3>
                            @if($event->is_featured)
                                <span class="px-2 py-1 rounded text-xs bg-gruvbox-light-yellow/20 text-gruvbox-light-yellow">
                                    Featured
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-2">{{ Str::limit($event->description, 100) }}</p>
                        <div class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 space-y-1">
                            <p>📅 {{ $event->start_date->format('M d, Y - h:i A') }} to {{ $event->end_date->format('M d, Y - h:i A') }}</p>
                            @if($event->location)
                                <p>📍 {{ $event->location }}</p>
                            @endif
                            @if($event->organizer)
                                <p>👤 {{ $event->organizer }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('admin.events.toggle', $event) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gruvbox-light-yellow dark:bg-gruvbox-dark-yellow text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                                {{ $event->is_featured ? 'Unfeature' : 'Feature' }}
                            </button>
                        </form>
                        <a href="{{ route('events.show', $event) }}" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity text-sm">
                            View
                        </a>
                        <form action="{{ route('admin.events.delete', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
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
            <p class="text-center text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 py-8">No events found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $events->links() }}
    </div>
</x-app-layout>
