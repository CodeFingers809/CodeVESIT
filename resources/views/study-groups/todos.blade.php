<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    {{ $studyGroup->name }} - Todos
                </h2>
            </div>
            @if($isModerator)
                <button onclick="document.getElementById('createTodoModal').style.display='block'" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-medium">
                    Add Todo
                </button>
            @endif
        </div>
    </x-slot>

    @include('study-groups.partials.navigation-tabs')

    <!-- Todos List -->
    @if($todos->isEmpty())
        <div class="p-8 text-center rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg4 dark:text-gruvbox-dark-fg4 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">No todos yet</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($todos as $todo)
                <div class="p-4 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                    <div class="flex items-start gap-4">
                        <form method="POST" action="{{ route('study-groups.todos.toggle', $todo) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="mt-1">
                                @if($todo->isCompletedBy(auth()->user()))
                                    <svg class="w-6 h-6 text-gruvbox-light-green dark:text-gruvbox-dark-green" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <div class="w-6 h-6 rounded-full border-2 border-gruvbox-light-fg4 dark:border-gruvbox-dark-fg4"></div>
                                @endif
                            </button>
                        </form>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ $todo->title }}</h4>
                                <span class="px-2 py-0.5 text-xs rounded {{ $todo->priority === 'high' ? 'bg-gruvbox-light-red/20 text-gruvbox-light-red dark:bg-gruvbox-dark-red/20 dark:text-gruvbox-dark-red' : ($todo->priority === 'medium' ? 'bg-gruvbox-light-yellow/20 text-gruvbox-light-yellow dark:bg-gruvbox-dark-yellow/20 dark:text-gruvbox-dark-yellow' : 'bg-gruvbox-light-green/20 text-gruvbox-light-green dark:bg-gruvbox-dark-green/20 dark:text-gruvbox-dark-green') }}">
                                    {{ ucfirst($todo->priority) }}
                                </span>
                            </div>
                            @if($todo->description)
                                <p class="text-sm text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-2">{{ $todo->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                                @if($todo->due_date)
                                    <span>Due: {{ $todo->due_date->format('M d, Y') }}</span>
                                @endif
                                <span>{{ $todo->completions->where('completed', true)->count() }}/{{ $studyGroup->members()->count() }} completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Create Todo Modal -->
    @if($isModerator)
        <div id="createTodoModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-lg p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <h3 class="text-xl font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Create Todo</h3>
                <form method="POST" action="{{ route('study-groups.todos.store', $studyGroup) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Title *</label>
                        <input type="text" name="title" required class="w-full rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Due Date</label>
                        <input type="date" name="due_date" class="w-full rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">Priority *</label>
                        <select name="priority" required class="w-full rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity">
                            Create
                        </button>
                        <button type="button" onclick="document.getElementById('createTodoModal').style.display='none'" class="px-4 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
