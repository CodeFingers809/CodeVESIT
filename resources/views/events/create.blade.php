<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Request Event
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-aqua/20 dark:bg-gruvbox-dark-aqua/20 border border-gruvbox-light-aqua/50 dark:border-gruvbox-dark-aqua/50">
                <p class="text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    <strong>Note:</strong> Your event request will be reviewed by admins. You'll be notified once it's approved and published.
                </p>
            </div>

            <form action="{{ route('events.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Event Title <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <input type="text" id="title" name="title" required value="{{ old('title') }}"
                               placeholder="Enter event title"
                               class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                        @error('title')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Description <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <textarea id="description" name="description" rows="6" required
                                  placeholder="Describe your event in detail"
                                  class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                                Start Date & Time <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                            </label>
                            <input type="datetime-local" id="start_date" name="start_date" required value="{{ old('start_date') }}"
                                   class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            @error('start_date')
                                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                                End Date & Time (Optional)
                            </label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                   class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            @error('end_date')
                                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Location <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <input type="text" id="location" name="location" required value="{{ old('location') }}"
                               placeholder="Event venue or location"
                               class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                        @error('location')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="organizer" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Organizer <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <input type="text" id="organizer" name="organizer" required value="{{ old('organizer') }}"
                               placeholder="Organizing body/committee"
                               class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                        @error('organizer')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                                Contact Email
                            </label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}"
                                   placeholder="contact@example.com"
                                   class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            @error('contact_email')
                                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                                Contact Phone
                            </label>
                            <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}"
                                   placeholder="+91 1234567890"
                                   class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                            @error('contact_phone')
                                <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit"
                            class="px-6 py-3 bg-gruvbox-light-aqua dark:bg-gruvbox-dark-aqua text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Submit Request
                    </button>
                    <a href="{{ route('events.index') }}"
                       class="px-6 py-3 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
