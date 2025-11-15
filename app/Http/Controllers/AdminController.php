<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\BlogRequest;
use App\Models\EventRequest;
use App\Models\Report;
use App\Models\User;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Forum;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(): View
    {
        $pendingStudyGroups = StudyGroup::where('status', 'pending')->count();
        $pendingBlogs = BlogRequest::where('status', 'pending')->count();
        $pendingEvents = EventRequest::where('status', 'pending')->count();
        $pendingReports = Report::where('status', 'pending')->count();

        return view('admin.index', compact(
            'pendingStudyGroups',
            'pendingBlogs',
            'pendingEvents',
            'pendingReports'
        ));
    }

    // Study Groups Management
    public function studyGroups(): View
    {
        $studyGroups = StudyGroup::with('creator')->latest()->paginate(20);
        return view('admin.study-groups', compact('studyGroups'));
    }

    public function approveStudyGroup(StudyGroup $studyGroup)
    {
        $studyGroup->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Study group approved successfully!');
    }

    public function rejectStudyGroup(StudyGroup $studyGroup)
    {
        $studyGroup->update(['status' => 'rejected']);
        return back()->with('success', 'Study group rejected successfully!');
    }

    // Blog Requests Management
    public function blogRequests(): View
    {
        $blogRequests = BlogRequest::with('user')->latest()->paginate(20);
        return view('admin.blog-requests', compact('blogRequests'));
    }

    public function approveBlogRequest(BlogRequest $request)
    {
        // Generate unique slug from title
        $slug = \Illuminate\Support\Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        // Ensure slug is unique
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create the blog from the request
        Blog::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'document_path' => $request->document_path,
            'is_published' => true,
            'published_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Delete the blog request after approval
        $request->delete();

        return back()->with('success', 'Blog request approved and published!');
    }

    public function rejectBlogRequest(BlogRequest $request, Request $httpRequest)
    {
        $validated = $httpRequest->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        // Delete the blog request after rejection
        $request->delete();

        return back()->with('success', 'Blog request rejected!');
    }

    // Event Requests Management
    public function eventRequests(): View
    {
        $eventRequests = EventRequest::with('organizer')->latest()->paginate(20);
        return view('admin.event-requests', compact('eventRequests'));
    }

    public function approveEventRequest(EventRequest $request)
    {
        $request->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Generate unique slug from title
        $slug = \Illuminate\Support\Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        // Ensure slug is unique
        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create the event from the request
        Event::create([
            'title' => $request->title,
            'slug' => $slug,
            'organizer_id' => $request->organizer_id,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'is_published' => true,
        ]);

        return back()->with('success', 'Event request approved and published!');
    }

    public function rejectEventRequest(EventRequest $request)
    {
        $request->update(['status' => 'rejected']);
        return back()->with('success', 'Event request rejected!');
    }

    // Reports Management
    public function reports(): View
    {
        $reports = Report::with(['reporter', 'reportable'])->latest()->paginate(20);
        return view('admin.reports', compact('reports'));
    }

    public function resolveReport(Report $report)
    {
        $report->update([
            'status' => 'resolved',
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Report marked as resolved!');
    }

    public function dismissReport(Report $report)
    {
        $report->update([
            'status' => 'dismissed',
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Report dismissed!');
    }

    // User Management
    public function users(): View
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUserRole(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Cannot change your own role!']);
        }

        $user->update([
            'role' => $user->role === 'admin' ? 'user' : 'admin',
        ]);

        return back()->with('success', 'User role updated!');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Cannot change your own status!']);
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return back()->with('success', 'User status updated!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Cannot delete your own account!']);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    // Blogs Management
    public function blogs(): View
    {
        $blogs = Blog::with('user')->latest()->paginate(20);
        return view('admin.blogs', compact('blogs'));
    }

    public function updateBlog(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $blog->update($validated);

        return back()->with('success', 'Blog updated successfully!');
    }

    public function toggleBlogFeature(Blog $blog)
    {
        $blog->update(['is_published' => !$blog->is_published]);
        return back()->with('success', 'Blog visibility toggled!');
    }

    public function deleteBlog(Blog $blog)
    {
        $blog->delete();
        return back()->with('success', 'Blog deleted successfully!');
    }

    // Events Management
    public function events(): View
    {
        $events = Event::with('organizer')->latest()->paginate(20);
        return view('admin.events', compact('events'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_published' => 'boolean',
        ]);

        $event->update($validated);

        return back()->with('success', 'Event updated successfully!');
    }

    public function toggleEventFeature(Event $event)
    {
        $event->update(['is_published' => !$event->is_published]);
        return back()->with('success', 'Event visibility toggled!');
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted successfully!');
    }

    // Forums Management
    public function forums(): View
    {
        $forums = Forum::withCount('posts')->latest()->paginate(20);
        return view('admin.forums', compact('forums'));
    }

    public function storeForum(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        Forum::create($validated);

        return back()->with('success', 'Forum created successfully!');
    }

    public function updateForum(Request $request, Forum $forum)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $forum->update($validated);

        return back()->with('success', 'Forum updated successfully!');
    }

    public function deleteForum(Forum $forum)
    {
        $forum->delete();
        return back()->with('success', 'Forum deleted successfully!');
    }

    // Database Overview (Read-only)
    public function database(): View
    {
        $tables = [
            'users' => User::count(),
            'study_groups' => StudyGroup::count(),
            'blogs' => Blog::count(),
            'events' => Event::count(),
            'forums' => Forum::count(),
            'forum_posts' => ForumPost::count(),
            'blog_requests' => BlogRequest::count(),
            'event_requests' => EventRequest::count(),
            'reports' => Report::count(),
        ];

        return view('admin.database', compact('tables'));
    }
}
