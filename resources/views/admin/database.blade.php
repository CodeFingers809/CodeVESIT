<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Database Overview
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Admin Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tables as $table => $count)
            <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
                <h3 class="text-sm font-medium text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 uppercase">{{ str_replace('_', ' ', $table) }}</h3>
                <p class="mt-2 text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ number_format($count) }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>
