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
     * Parse DOCX content from Cloudinary URL
     */
    private function parseDocx(string $url): ?string
    {
        try {
            // Download the file temporarily
            $tempFile = tempnam(sys_get_temp_dir(), 'blog_');
            file_put_contents($tempFile, file_get_contents($url));

            // Load the docx file
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempFile);

            // Extract text content
            $content = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $content .= $element->getText() . "\n\n";
                    } elseif (method_exists($element, 'getElements')) {
                        // For tables and other complex elements
                        $content .= $this->extractText($element) . "\n\n";
                    }
                }
            }

            // Clean up
            @unlink($tempFile);

            return nl2br(e(trim($content)));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Recursively extract text from complex elements
     */
    private function extractText($element): string
    {
        $text = '';
        if (method_exists($element, 'getText')) {
            $text .= $element->getText() . ' ';
        }
        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $childElement) {
                $text .= $this->extractText($childElement);
            }
        }
        return $text;
    }
}
