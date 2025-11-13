<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(): View
    {
        $forums = Forum::where('is_active', true)->withCount('posts')->get();
        return view('forums.index', compact('forums'));
    }

    public function show(Forum $forum): View
    {
        $posts = $forum->posts()->with('user')->withCount('comments')->latest()->paginate(20);
        return view('forums.show', compact('forum', 'posts'));
    }

    public function storePost(Request $request, Forum $forum)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $forum->posts()->create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Post created successfully!');
    }

    public function showPost(ForumPost $post): View
    {
        $post->increment('views');
        $post->load(['user', 'comments.user', 'comments.replies.user']);
        
        return view('forums.post', compact('post'));
    }

    public function storeComment(Request $request, ForumPost $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_comments,id',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Comment posted!');
    }

    public function reportPost(Request $request, ForumPost $post)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        Report::create([
            'reported_by' => auth()->id(),
            'reportable_type' => ForumPost::class,
            'reportable_id' => $post->id,
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Post reported. Our team will review it.');
    }

    public function reportComment(Request $request, ForumComment $comment)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        Report::create([
            'reported_by' => auth()->id(),
            'reportable_type' => ForumComment::class,
            'reportable_id' => $comment->id,
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Comment reported. Our team will review it.');
    }
}
