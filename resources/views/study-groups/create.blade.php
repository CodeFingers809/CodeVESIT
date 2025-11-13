<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Request New Study Group
        </h2>
    </x-slot>

    <div class="max-w-2xl">
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <form method="POST" action="{{ route('study-groups.store') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                        Study Group Name *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        placeholder="e.g., Data Structures - SE Div A"
                        class="w-full rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:border-gruvbox-light-blue dark:focus:border-gruvbox-dark-blue focus:ring focus:ring-gruvbox-light-blue/20 dark:focus:ring-gruvbox-dark-blue/20"
                    >
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                        Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4" 
                        placeholder="Describe the purpose of this study group..."
                        class="w-full rounded-lg bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 focus:border-gruvbox-light-blue dark:focus:border-gruvbox-dark-blue focus:ring focus:ring-gruvbox-light-blue/20 dark:focus:ring-gruvbox-dark-blue/20"
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Info Box -->
                <div class="p-4 rounded-lg bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 border border-gruvbox-light-yellow dark:border-gruvbox-dark-yellow">
                    <div class="flex">
                        <svg class="w-5 h-5 text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-gruvbox-light-fg1 dark:text-gruvbox-dark-fg1">
                            <p class="font-medium mb-1">Request Process</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Your request will be reviewed by an admin</li>
                                <li>Once approved, a unique join code will be generated</li>
                                <li>You'll automatically become a moderator of the group</li>
                                <li>Share the join code with your classmates to let them join</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-medium">
                        Submit Request
                    </button>
                    <a href="{{ route('study-groups.index') }}" class="px-6 py-2 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
