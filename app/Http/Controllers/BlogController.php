<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogRequest;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(): View
    {
        $blogs = Blog::where('is_published', true)
            ->with('user')
            ->latest('published_at')
            ->paginate(12);

        return view('blogs.index', compact('blogs'));
    }

    public function myBlogs(): View
    {
        $blogs = auth()->user()->blogs()->latest()->get();
        $requests = auth()->user()->blogRequests()->latest()->get();

        return view('blogs.my-blogs', compact('blogs', 'requests'));
    }

    public function create(): View
    {
        $maxFileSize = DocumentService::getMaxFileSizeFormatted();
        return view('blogs.create', compact('maxFileSize'));
    }

    public function store(Request $request)
    {
        $maxFileSizeKb = DocumentService::MAX_FILE_SIZE / 1024; // Convert to KB for validation

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|min:50|max:300',
            'document' => "required|file|mimes:docx|max:{$maxFileSizeKb}", // 5MB max
        ], [
            'document.max' => 'The document size must not exceed ' . DocumentService::getMaxFileSizeFormatted() . '.',
        ]);

        try {
            // Upload to Cloudinary with compression
            $documentUrl = $this->documentService->uploadDocument(
                $request->file('document'),
                'blogs'
            );

            BlogRequest::create([
                'title' => $validated['title'],
                'excerpt' => $validated['excerpt'],
                'content' => '', // Empty as content is in the document
                'document_path' => $documentUrl,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('blogs.my')
                ->with('success', 'Blog document submitted for review!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['document' => $e->getMessage()]);
        }
    }

    public function show(Blog $blog): View
    {
        $blog->increment('views');
        $blog->load('user');

        // Parse docx content if document_path exists
        $parsedContent = null;
        if ($blog->document_path) {
            try {
                $parsedContent = $this->parseDocx($blog->document_path);
            } catch (\Exception $e) {
                // If parsing fails, just show the regular content
                $parsedContent = null;
            }
        }

        return view('blogs.show', compact('blog', 'parsedContent'));
    }

    /**
     * Parse DOCX content from Cloudinary URL and convert to Gruvbox-styled HTML
     */
    private function parseDocx(string $url): ?string
    {
        try {
            // Download the file temporarily
            $tempFile = tempnam(sys_get_temp_dir(), 'blog_');
            file_put_contents($tempFile, file_get_contents($url));

            // Load the docx file
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempFile);

            // Convert to HTML
            $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);

            // Save to temporary HTML file
            $htmlFile = tempnam(sys_get_temp_dir(), 'blog_html_');
            $htmlWriter->save($htmlFile);

            // Read the HTML content
            $htmlContent = file_get_contents($htmlFile);

            // Clean up temporary files
            @unlink($tempFile);
            @unlink($htmlFile);

            // Apply Gruvbox styling
            return $this->applyGruvboxStyling($htmlContent);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Apply Gruvbox theme styling to HTML content
     */
    private function applyGruvboxStyling(string $html): string
    {
        // Extract body content only (remove HTML wrapper from PHPWord output)
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $html = $matches[1];
        }

        // Define Gruvbox colors
        $gruvboxStyles = '
            <style>
                .docx-content h1 {
                    font-size: 2em;
                    font-weight: 700;
                    margin-top: 1.5em;
                    margin-bottom: 0.75em;
                    color: #fb4934; /* Gruvbox red */
                    line-height: 1.2;
                }
                .docx-content h2 {
                    font-size: 1.75em;
                    font-weight: 600;
                    margin-top: 1.25em;
                    margin-bottom: 0.65em;
                    color: #fabd2f; /* Gruvbox yellow */
                    line-height: 1.3;
                }
                .docx-content h3 {
                    font-size: 1.5em;
                    font-weight: 600;
                    margin-top: 1em;
                    margin-bottom: 0.5em;
                    color: #b8bb26; /* Gruvbox green */
                    line-height: 1.3;
                }
                .docx-content h4 {
                    font-size: 1.25em;
                    font-weight: 600;
                    margin-top: 1em;
                    margin-bottom: 0.5em;
                    color: #83a598; /* Gruvbox blue */
                    line-height: 1.4;
                }
                .docx-content h5, .docx-content h6 {
                    font-size: 1.1em;
                    font-weight: 600;
                    margin-top: 0.75em;
                    margin-bottom: 0.5em;
                    color: #d3869b; /* Gruvbox purple */
                    line-height: 1.4;
                }
                .docx-content p {
                    margin-bottom: 1em;
                    line-height: 1.7;
                }
                .docx-content strong, .docx-content b {
                    font-weight: 700;
                    color: #fe8019; /* Gruvbox orange */
                }
                .docx-content em, .docx-content i {
                    font-style: italic;
                    color: #8ec07c; /* Gruvbox aqua */
                }
                .docx-content ul, .docx-content ol {
                    margin-left: 1.5em;
                    margin-bottom: 1em;
                }
                .docx-content li {
                    margin-bottom: 0.5em;
                    line-height: 1.6;
                }
                .docx-content ul {
                    list-style-type: disc;
                }
                .docx-content ol {
                    list-style-type: decimal;
                }
                .docx-content table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 1.5em 0;
                }
                .docx-content th {
                    background-color: rgba(251, 73, 52, 0.1); /* Gruvbox red with opacity */
                    font-weight: 600;
                    padding: 0.75em;
                    border: 1px solid rgba(235, 219, 178, 0.2);
                    text-align: left;
                }
                .docx-content td {
                    padding: 0.75em;
                    border: 1px solid rgba(235, 219, 178, 0.2);
                }
                .docx-content tr:nth-child(even) {
                    background-color: rgba(235, 219, 178, 0.05);
                }
                .docx-content blockquote {
                    border-left: 4px solid #b8bb26; /* Gruvbox green */
                    padding-left: 1em;
                    margin: 1em 0;
                    font-style: italic;
                    opacity: 0.9;
                }
                .docx-content code {
                    background-color: rgba(235, 219, 178, 0.1);
                    padding: 0.2em 0.4em;
                    border-radius: 3px;
                    font-family: monospace;
                    font-size: 0.9em;
                    color: #fe8019; /* Gruvbox orange */
                }
                .docx-content pre {
                    background-color: rgba(40, 40, 40, 0.3);
                    padding: 1em;
                    border-radius: 5px;
                    overflow-x: auto;
                    margin: 1em 0;
                }
                .docx-content pre code {
                    background-color: transparent;
                    padding: 0;
                }
                .docx-content a {
                    color: #83a598; /* Gruvbox blue */
                    text-decoration: underline;
                }
                .docx-content a:hover {
                    color: #458588; /* Gruvbox blue dark */
                }
                .docx-content img {
                    max-width: 100%;
                    height: auto;
                    margin: 1em 0;
                    border-radius: 5px;
                }
            </style>
        ';

        // Wrap content in styled div
        return $gruvboxStyles . '<div class="docx-content">' . $html . '</div>';
    }
}
