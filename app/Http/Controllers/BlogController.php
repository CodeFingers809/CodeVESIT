<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class BlogController extends Controller
{
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
            'excerpt' => 'required|string|min:50|max:300',
            'document' => 'required|file|mimes:docx|max:10240', // 10MB max
        ]);

        // Store the document file
        $documentPath = $request->file('document')->store('blogs', 'public');

        BlogRequest::create([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => '', // Empty as content is in the document
            'document_path' => $documentPath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('blogs.my')
            ->with('success', 'Blog document submitted for review!');
    }

    public function show(Blog $blog): View
    {
        $blog->increment('views');
        $blog->load('user');

        return view('blogs.show', compact('blog'));
    }
}
