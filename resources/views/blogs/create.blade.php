<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Write Blog
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-blue/20 dark:bg-gruvbox-dark-blue/20 border border-gruvbox-light-blue/50 dark:border-gruvbox-dark-blue/50">
                <p class="text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    <strong>Note:</strong> Your blog will be submitted for admin review before publication. You'll be notified once it's approved.
                </p>
            </div>

            <form action="{{ route('blogs.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Title <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <input type="text" id="title" name="title" required value="{{ old('title') }}"
                               placeholder="Enter your blog title"
                               class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 text-lg">
                        @error('title')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Excerpt (Optional)
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="2"
                                  placeholder="A brief summary of your blog (optional)"
                                  class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Content <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <textarea id="content" name="content" rows="20" required
                                  placeholder="Write your blog content here..."
                                  class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 font-mono">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            Minimum 100 characters required
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit"
                            class="px-6 py-3 bg-gruvbox-light-purple dark:bg-gruvbox-dark-purple text-gruvbox-light-bg0 dark:text-gruvbox-dark-bg0 rounded-lg hover:opacity-90 transition-opacity font-semibold">
                        Submit for Review
                    </button>
                    <a href="{{ route('blogs.my') }}"
                       class="px-6 py-3 bg-gruvbox-light-bg2 dark:bg-gruvbox-dark-bg2 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 rounded-lg hover:bg-gruvbox-light-bg3 dark:hover:bg-gruvbox-dark-bg3 transition-colors font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
