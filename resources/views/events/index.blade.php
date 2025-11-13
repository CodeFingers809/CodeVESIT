<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                Events
            </h2>
            <a href="{{ route('events.create') }}"
               class="px-4 py-2 bg-gruvbox-light-blue dark:bg-gruvbox-dark-blue text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                Request Event
            </a>
        </div>
    </x-slot>

    <div class="mb-6 p-6 rounded-lg bg-gradient-to-r from-gruvbox-light-aqua/80 to-gruvbox-light-blue/80 dark:from-gruvbox-dark-aqua/80 dark:to-gruvbox-dark-blue/80">
        <h3 class="text-2xl font-bold text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0">College Events</h3>
        <p class="mt-2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Stay updated with upcoming college events and activities</p>
    </div>

    <!-- Upcoming Events -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Upcoming Events</h3>

        @if($upcomingEvents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 hover:border-gruvbox-light-aqua dark:hover:border-gruvbox-dark-aqua transition-colors">
                        @if($event->is_featured)
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow mb-2">
                                Featured
                            </span>
                        @endif

                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                        @endif

                        <h4 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">{{ $event->title }}</h4>

                        <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 mb-3 line-clamp-2">{{ $event->description }}</p>

                        <div class="space-y-2 text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $event->start_date->format('M d, Y - h:i A') }}
                            </div>

                            @if($event->location)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $event->location }}
                                </div>
                            @endif

                            @if($event->organizer)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $event->organizer }}
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1">
                <svg class="w-16 h-16 mx-auto text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">No upcoming events at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Past Events -->
    @if($pastEvents->count() > 0)
        <div>
            <h3 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-4">Past Events</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pastEvents as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 opacity-75 hover:opacity-100 transition-opacity">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                        @endif

                        <h4 class="text-lg font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">{{ $event->title }}</h4>

                        <p class="text-sm text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            {{ $event->start_date->format('M d, Y') }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</x-app-layout>
