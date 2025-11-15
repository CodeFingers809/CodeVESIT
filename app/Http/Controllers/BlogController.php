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
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
        ], [
            'content.min' => 'Blog content must be at least 100 characters.',
        ]);

        try {
            BlogRequest::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('blogs.my')
                ->with('success', 'Blog submitted for review!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['content' => $e->getMessage()]);
        }
    }

    public function show(Blog $blog): View
    {
        $blog->increment('views');
        $blog->load('user');

        return view('blogs.show', compact('blog'));
    }

    /**
     * Upload image for blog editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        try {
            $imageUrl = $this->documentService->uploadImage(
                $request->file('image'),
                'blog-images'
            );

            return response()->json([
                'success' => true,
                'url' => $imageUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
