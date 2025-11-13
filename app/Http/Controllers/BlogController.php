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
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
        ]);

        BlogRequest::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('blogs.my')
            ->with('success', 'Blog submitted for review!');
    }

    public function show(Blog $blog): View
    {
        $blog->increment('views');
        $blog->load('user');

        return view('blogs.show', compact('blog'));
    }
}
