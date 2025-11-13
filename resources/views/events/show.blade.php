<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('events.index') }}" class="text-gruvbox-light-blue dark:text-gruvbox-dark-blue hover:underline">
            ← Back to Events
        </a>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        @if($event->image)
            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover rounded-lg mb-6">
        @endif

        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">{{ $event->title }}</h1>

                    @if($event->is_featured)
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-gruvbox-light-yellow/20 dark:bg-gruvbox-dark-yellow/20 text-gruvbox-light-yellow dark:text-gruvbox-dark-yellow">
                            Featured Event
                        </span>
                    @endif
                </div>

                @if($event->start_date > now())
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gruvbox-light-green/20 dark:bg-gruvbox-dark-green/20 text-gruvbox-light-green dark:text-gruvbox-dark-green">
                        Upcoming
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gruvbox-light-fg3/20 dark:bg-gruvbox-dark-fg3/20 text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                        Past Event
                    </span>
                @endif
            </div>

            <div class="border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 my-6"></div>

            <!-- Event Details -->
            <div class="space-y-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 mr-3 text-gruvbox-light-aqua dark:text-gruvbox-dark-aqua mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Date & Time</p>
                        <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">
                            {{ $event->start_date->format('l, F d, Y') }} at {{ $event->start_date->format('h:i A') }}
                            @if($event->end_date)
                                <br>to {{ $event->end_date->format('l, F d, Y') }} at {{ $event->end_date->format('h:i A') }}
                            @endif
                        </p>
                    </div>
                </div>

                @if($event->location)
                    <div class="flex items-start">
                        <svg class="w-6 h-6 mr-3 text-gruvbox-light-aqua dark:text-gruvbox-dark-aqua mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Location</p>
                            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">{{ $event->location }}</p>
                        </div>
                    </div>
                @endif

                @if($event->organizer)
                    <div class="flex items-start">
                        <svg class="w-6 h-6 mr-3 text-gruvbox-light-aqua dark:text-gruvbox-dark-aqua mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">Organizer</p>
                            <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">{{ $event->organizer }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 my-6"></div>

            <!-- Description -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-3">About This Event</h2>
                <p class="text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 whitespace-pre-wrap leading-relaxed">{{ $event->description }}</p>
            </div>

            <!-- Contact Information -->
            @if($event->contact_email || $event->contact_phone)
                <div class="border-t border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 my-6"></div>

                <div>
                    <h2 class="text-xl font-bold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-3">Contact Information</h2>
                    <div class="space-y-2">
                        @if($event->contact_email)
                            <div class="flex items-center text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:{{ $event->contact_email }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">
                                    {{ $event->contact_email }}
                                </a>
                            </div>
                        @endif

                        @if($event->contact_phone)
                            <div class="flex items-center text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:{{ $event->contact_phone }}" class="hover:text-gruvbox-light-blue dark:hover:text-gruvbox-dark-blue">
                                    {{ $event->contact_phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
