<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            My Calendar
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 border border-gruvbox-light-green dark:border-gruvbox-dark-green">
            <p class="text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Add New Event Button -->
    <div class="mb-6">
        <button onclick="document.getElementById('createEventModal').classList.remove('hidden')"
                class="px-6 py-3 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Task
        </button>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Upcoming Tasks -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Upcoming Tasks</h3>

            @forelse($upcomingEvents as $event)
                <div class="mb-4 p-4 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $event->title }}</h4>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($event->priority === 'high') bg-gruvbox-light-red dark:bg-gruvbox-dark-red
                            @elseif($event->priority === 'medium') bg-gruvbox-light-yellow dark:bg-gruvbox-dark-yellow
                            @else bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue
                            @endif
                            text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0">
                            {{ ucfirst($event->priority) }}
                        </span>
                    </div>

                    @if($event->description)
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-2">{{ $event->description }}</p>
                    @endif

                    <div class="flex items-center text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $event->event_date->format('M d, Y - h:i A') }}
                    </div>

                    <div class="flex gap-2">
                        <form action="{{ route('calendar.complete', $event) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 bg-gruvbox-light-green dark:bg-gruvbox-dark-green text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded text-sm hover:opacity-90 transition-opacity">
                                Complete
                            </button>
                        </form>
                        <button onclick="openEditModal({{ json_encode($event) }})"
                                class="px-3 py-1 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded text-sm hover:opacity-90 transition-opacity">
                            Edit
                        </button>
                        <form action="{{ route('calendar.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-gruvbox-light-red dark:bg-gruvbox-dark-red text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded text-sm hover:opacity-90 transition-opacity">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 text-center py-8">No upcoming tasks</p>
            @endforelse
        </div>

        <!-- Completed Tasks -->
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Completed Tasks</h3>

            @forelse($completedEvents as $event)
                <div class="mb-4 p-4 rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 opacity-75">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 line-through">{{ $event->title }}</h4>
                        <svg class="w-5 h-5 text-gruvbox-light-green dark:text-gruvbox-dark-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    @if($event->description)
                        <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-2">{{ $event->description }}</p>
                    @endif

                    <div class="flex items-center text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $event->event_date->format('M d, Y - h:i A') }}
                    </div>
                </div>
            @empty
                <p class="text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 text-center py-8">No completed tasks</p>
            @endforelse
        </div>
    </div>

    <!-- Create Event Modal -->
    <div id="createEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Add New Task</h3>

            <form action="{{ route('calendar.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Title</label>
                        <input type="text" name="title" required
                               class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Date & Time</label>
                        <input type="datetime-local" name="event_date" required
                               class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Priority</label>
                        <select name="priority"
                                class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Add Task
                    </button>
                    <button type="button" onclick="document.getElementById('createEventModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="editEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Edit Task</h3>

            <form id="editEventForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Title</label>
                        <input type="text" name="title" id="edit_title" required
                               class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3"
                                  class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Date & Time</label>
                        <input type="datetime-local" name="event_date" id="edit_event_date" required
                               class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-1">Priority</label>
                        <select name="priority" id="edit_priority"
                                class="w-full px-3 py-2 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Update Task
                    </button>
                    <button type="button" onclick="document.getElementById('editEventModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(event) {
            document.getElementById('editEventForm').action = `/calendar/${event.id}`;
            document.getElementById('edit_title').value = event.title;
            document.getElementById('edit_description').value = event.description || '';
            document.getElementById('edit_event_date').value = event.event_date.replace(' ', 'T').substring(0, 16);
            document.getElementById('edit_priority').value = event.priority;
            document.getElementById('editEventModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
