<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
            Write Blog
        </h2>
    </x-slot>

    <!-- Include Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <div class="max-w-4xl mx-auto">
        <div class="p-6 rounded-lg bg-gruvbox-light-bg1 dark:bg-gruvbox-dark-bg1 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3">
            <div class="mb-6 p-4 rounded-lg bg-gruvbox-light-blue/20 dark:bg-gruvbox-dark-blue/20 border border-gruvbox-light-blue/50 dark:border-gruvbox-dark-blue/50">
                <p class="text-sm text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0">
                    <strong>Note:</strong> Your blog will be submitted for admin review before publication. You'll be notified once it's approved.
                </p>
            </div>

            <form action="{{ route('blogs.store') }}" method="POST" id="blog-form">
                @csrf

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Blog Title <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <input type="text" id="title" name="title" required value="{{ old('title') }}"
                               placeholder="Enter an engaging title for your blog"
                               class="w-full px-4 py-3 rounded-lg bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 border border-gruvbox-light-bg3 dark:border-gruvbox-dark-bg3 text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 text-lg focus:outline-none focus:border-gruvbox-light-blue dark:focus:border-gruvbox-dark-blue">
                        @error('title')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rich Text Editor -->
                    <div>
                        <label for="editor" class="block text-sm font-medium text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2">
                            Blog Content <span class="text-gruvbox-light-red dark:text-gruvbox-dark-red">*</span>
                        </label>
                        <div id="editor" class="bg-gruvbox-light-bg0 dark:bg-gruvbox-dark-bg0 rounded-lg" style="height: 400px;"></div>
                        <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                        @error('content')
                            <p class="mt-2 text-sm text-gruvbox-light-red dark:text-gruvbox-dark-red">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gruvbox-light-fg3 dark:text-gruvbox-dark-fg3">
                            Minimum 100 characters. Use the toolbar to format text, add headings, lists, links, and images.
                        </p>
                    </div>

                    <!-- Guidelines -->
                    <div class="p-4 rounded-lg bg-gruvbox-light-aqua/20 dark:bg-gruvbox-dark-aqua/20 border border-gruvbox-light-aqua/50 dark:border-gruvbox-dark-aqua/50">
                        <h4 class="text-sm font-semibold text-gruvbox-light-fg0 dark:text-gruvbox-dark-fg0 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Writing Guidelines
                        </h4>
                        <ul class="text-xs text-gruvbox-light-fg2 dark:text-gruvbox-dark-fg2 space-y-1 list-disc list-inside ml-4">
                            <li>Use headings to structure your content (Heading 1 for main sections, Heading 2 for subsections)</li>
                            <li>Add images by clicking the image icon in the toolbar</li>
                            <li>Format text with bold, italic, lists, quotes, and code blocks</li>
                            <li>Ensure content is original and follows community guidelines</li>
                            <li>Images are automatically uploaded and optimized</li>
                        </ul>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit" id="submit-btn"
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

    <!-- Include Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <script>
        // Initialize Quill editor with Gruvbox theme
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['blockquote', 'code-block'],
                        ['link', 'image'],
                        ['clean']
                    ],
                    handlers: {
                        image: imageHandler
                    }
                }
            },
            placeholder: 'Start writing your blog here...'
        });

        // Set old content if exists (for validation errors)
        @if(old('content'))
            quill.root.innerHTML = {!! json_encode(old('content')) !!};
        @endif

        // Custom image handler for Cloudinary upload
        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (file) {
                    // Show uploading indicator
                    const range = quill.getSelection(true);
                    quill.insertText(range.index, 'Uploading image...');
                    quill.setSelection(range.index + 19);

                    // Upload to server
                    const formData = new FormData();
                    formData.append('image', file);

                    try {
                        const response = await fetch('{{ route("blogs.upload-image") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Remove uploading text
                            quill.deleteText(range.index, 19);
                            // Insert image
                            quill.insertEmbed(range.index, 'image', data.url);
                            quill.setSelection(range.index + 1);
                        } else {
                            alert('Image upload failed: ' + data.message);
                            quill.deleteText(range.index, 19);
                        }
                    } catch (error) {
                        alert('Image upload failed. Please try again.');
                        quill.deleteText(range.index, 19);
                    }
                }
            };
        }

        // Submit form handler
        document.getElementById('blog-form').addEventListener('submit', function(e) {
            // Get HTML content from Quill
            const content = quill.root.innerHTML;

            // Check if content is empty (just <p><br></p> or similar)
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            const textContent = tempDiv.textContent || tempDiv.innerText || '';

            if (textContent.trim().length < 100) {
                e.preventDefault();
                alert('Blog content must be at least 100 characters.');
                return false;
            }

            // Set content to hidden field
            document.getElementById('content').value = content;
        });
    </script>

    <!-- Gruvbox Theme Customization for Quill -->
    <style>
        /* Quill toolbar styling - Light Mode */
        .ql-toolbar.ql-snow {
            background-color: #ebdbb2; /* Gruvbox light bg2 */
            border: 2px solid #d5c4a1; /* Gruvbox light bg3 */
            border-radius: 0.5rem 0.5rem 0 0;
            padding: 8px;
        }

        /* Quill toolbar styling - Dark Mode */
        .dark .ql-toolbar.ql-snow {
            background-color: #504945; /* Gruvbox dark bg2 */
            border: 2px solid #665c54; /* Gruvbox dark bg3 */
        }

        /* Quill editor container - Light Mode */
        .ql-container.ql-snow {
            background-color: #fbf1c7; /* Gruvbox light bg0 */
            border: 2px solid #d5c4a1; /* Gruvbox light bg3 */
            border-top: none;
            border-radius: 0 0 0.5rem 0.5rem;
            color: #3c3836; /* Gruvbox light fg0 */
            font-size: 16px;
        }

        /* Quill editor container - Dark Mode */
        .dark .ql-container.ql-snow {
            background-color: #282828; /* Gruvbox dark bg0 */
            border: 2px solid #665c54; /* Gruvbox dark bg3 */
            border-top: none;
            color: #ebdbb2; /* Gruvbox dark fg0 */
        }

        /* Toolbar buttons */
        .ql-toolbar.ql-snow .ql-picker-label,
        .ql-toolbar.ql-snow button {
            color: #3c3836;
        }

        .dark .ql-toolbar.ql-snow .ql-picker-label,
        .dark .ql-toolbar.ql-snow button {
            color: #ebdbb2;
        }

        /* Toolbar button hover/active backgrounds */
        .ql-toolbar.ql-snow button:hover,
        .ql-toolbar.ql-snow button.ql-active {
            background-color: rgba(131, 165, 152, 0.2); /* Gruvbox blue with opacity */
        }

        .dark .ql-toolbar.ql-snow button:hover,
        .dark .ql-toolbar.ql-snow button.ql-active {
            background-color: rgba(131, 165, 152, 0.3);
        }

        /* Toolbar icons - Light Mode */
        .ql-snow .ql-stroke {
            stroke: #3c3836; /* Gruvbox light fg0 */
        }

        .ql-snow .ql-fill {
            fill: #3c3836; /* Gruvbox light fg0 */
        }

        /* Toolbar icons - Dark Mode */
        .dark .ql-snow .ql-stroke {
            stroke: #ebdbb2; /* Gruvbox dark fg0 */
        }

        .dark .ql-snow .ql-fill {
            fill: #ebdbb2; /* Gruvbox dark fg0 */
        }

        /* Active/hover states for icons */
        .ql-snow.ql-toolbar button:hover .ql-stroke,
        .ql-snow.ql-toolbar button.ql-active .ql-stroke {
            stroke: #83a598; /* Gruvbox blue */
        }

        .ql-snow.ql-toolbar button:hover .ql-fill,
        .ql-snow.ql-toolbar button.ql-active .ql-fill {
            fill: #83a598; /* Gruvbox blue */
        }

        /* Picker dropdowns */
        .ql-snow .ql-picker-options {
            background-color: #ebdbb2;
            border: 1px solid #d5c4a1;
        }

        .dark .ql-snow .ql-picker-options {
            background-color: #504945;
            border: 1px solid #665c54;
        }

        .ql-snow .ql-picker-label:hover,
        .ql-snow .ql-picker-item:hover {
            color: #83a598; /* Gruvbox blue */
        }

        /* Editor content styling with Gruvbox colors */
        .ql-editor {
            min-height: 300px;
            padding: 20px;
        }

        .ql-editor h1 {
            color: #fb4934; /* Gruvbox red */
            font-size: 2em;
            font-weight: 700;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        .ql-editor h2 {
            color: #fabd2f; /* Gruvbox yellow */
            font-size: 1.75em;
            font-weight: 600;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        .ql-editor h3 {
            color: #b8bb26; /* Gruvbox green */
            font-size: 1.5em;
            font-weight: 600;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        .ql-editor strong {
            color: #fe8019; /* Gruvbox orange */
            font-weight: 700;
        }

        .ql-editor em {
            color: #8ec07c; /* Gruvbox aqua */
        }

        .ql-editor a {
            color: #83a598; /* Gruvbox blue */
        }

        .ql-editor blockquote {
            border-left: 4px solid #b8bb26;
            padding-left: 1em;
            margin-left: 0;
        }

        .ql-editor code,
        .ql-editor .ql-code-block {
            background-color: rgba(235, 219, 178, 0.2);
            color: #fe8019;
            font-family: 'Courier New', monospace;
        }

        .ql-editor pre {
            background-color: rgba(40, 40, 40, 0.3);
            padding: 1em;
            border-radius: 0.5rem;
        }

        /* Placeholder - Light Mode */
        .ql-editor.ql-blank::before {
            color: #7c6f64; /* Gruvbox light fg3 */
            font-style: italic;
        }

        /* Placeholder - Dark Mode */
        .dark .ql-editor.ql-blank::before {
            color: #a89984; /* Gruvbox dark fg3 */
        }

        /* Image handling */
        .ql-editor img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</x-app-layout>
