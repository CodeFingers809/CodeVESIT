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

            <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Blog Title <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <input type="text" id="title" name="title" required value="{{ old('title') }}"
                               placeholder="Enter your blog title"
                               class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 text-lg">
                        @error('title')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="document" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Blog Document (.docx) <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <input type="file" id="document" name="document" required accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                               class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gruvbox-light-purple dark:file:bg-gruvbox-dark-purple file:text-gruvbox-light-bg0 dark:file:text-gruvbox-dark-bg0 hover:file:opacity-90">
                        @error('document')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            Upload your blog as a Microsoft Word document (.docx format). Maximum file size: 10MB.
                        </p>
                    </div>

                    <div class="p-4 rounded-lg bg-gruvbox-light-aqua/20 dark:bg-gruvbox-dark-aqua/20 border border-gruvbox-light-aqua/50 dark:border-gruvbox-dark-aqua/50">
                        <h4 class="text-sm font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Document Guidelines
                        </h4>
                        <ul class="text-xs text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 space-y-1 list-disc list-inside ml-4">
                            <li>Use Microsoft Word (.docx) format only</li>
                            <li>Include proper headings, formatting, and structure in your document</li>
                            <li>Images and media can be embedded directly in the document</li>
                            <li>Ensure content is original and follows community guidelines</li>
                            <li>Your document will be rendered in the platform's theme after admin approval</li>
                        </ul>
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
