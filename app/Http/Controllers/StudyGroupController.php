<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\StudyGroupTodo;
use App\Models\StudyGroupAnnouncement;
use App\Models\StudyGroupMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudyGroupController extends Controller
{
    public function index(): View
    {
        $myGroups = auth()->user()->studyGroups()->where('status', 'approved')->get();
        $availableGroups = StudyGroup::where('status', 'approved')
            ->where('is_active', true)
            ->whereDoesntHave('members', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->get();

        return view('study-groups.index', compact('myGroups', 'availableGroups'));
    }

    public function create(): View
    {
        return view('study-groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $studyGroup = auth()->user()->createdStudyGroups()->create($validated);

        // Automatically make creator a member and moderator
        $studyGroup->members()->attach(auth()->id());
        $studyGroup->moderators()->attach(auth()->id(), ['assigned_by' => auth()->id()]);

        return redirect()->route('study-groups.index')
            ->with('success', 'Study group request submitted! Awaiting admin approval.');
    }

    public function show(StudyGroup $studyGroup): View
    {
        if (!$studyGroup->isApproved()) {
            abort(404, 'Study group not found or not approved.');
        }

        if (!$studyGroup->isMember(auth()->user())) {
            abort(403, 'You must be a member to view this study group.');
        }

        $isModerator = $studyGroup->isModerator(auth()->user());

        return view('study-groups.show', compact('studyGroup', 'isModerator'));
    }

    public function join(Request $request, StudyGroup $studyGroup)
    {
        $validated = $request->validate([
            'join_code' => 'required|string|size:8',
        ]);

        if ($studyGroup->join_code !== strtoupper($validated['join_code'])) {
            return back()->withErrors(['join_code' => 'Invalid join code.']);
        }

        if (!$studyGroup->isApproved()) {
            return back()->withErrors(['join_code' => 'This study group is not active.']);
        }

        if ($studyGroup->isMember(auth()->user())) {
            return back()->withErrors(['join_code' => 'You are already a member of this group.']);
        }

        $studyGroup->members()->attach(auth()->id());

        return redirect()->route('study-groups.show', $studyGroup)
            ->with('success', 'Successfully joined the study group!');
    }

    public function leave(StudyGroup $studyGroup)
    {
        if (!$studyGroup->isMember(auth()->user())) {
            return back()->withErrors(['error' => 'You are not a member of this group.']);
        }

        // Don't allow creator to leave
        if ($studyGroup->created_by === auth()->id()) {
            return back()->withErrors(['error' => 'Group creator cannot leave the group.']);
        }

        $studyGroup->members()->detach(auth()->id());
        $studyGroup->moderators()->detach(auth()->id());

        return redirect()->route('study-groups.index')
            ->with('success', 'You have left the study group.');
    }

    // Todos
    public function todos(StudyGroup $studyGroup): View
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        $todos = $studyGroup->todos()->with('completions')->orderBy('due_date')->get();
        $isModerator = $studyGroup->isModerator(auth()->user());

        return view('study-groups.todos', compact('studyGroup', 'todos', 'isModerator'));
    }

    public function storeTodo(Request $request, StudyGroup $studyGroup)
    {
        if (!$studyGroup->isModerator(auth()->user())) {
            abort(403, 'Only moderators can create todos.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $studyGroup->todos()->create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Todo created successfully!');
    }

    public function toggleTodo(Request $request, StudyGroupTodo $todo)
    {
        $completion = $todo->completions()->where('user_id', auth()->id())->first();

        if ($completion) {
            $completion->update([
                'completed' => !$completion->completed,
                'completed_at' => !$completion->completed ? now() : null,
            ]);
        } else {
            $todo->completions()->create([
                'user_id' => auth()->id(),
                'completed' => true,
                'completed_at' => now(),
            ]);
        }

        return back()->with('success', 'Todo updated!');
    }

    // Announcements
    public function announcements(StudyGroup $studyGroup): View
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        $announcements = $studyGroup->announcements()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        $isModerator = $studyGroup->isModerator(auth()->user());

        return view('study-groups.announcements', compact('studyGroup', 'announcements', 'isModerator'));
    }

    public function storeAnnouncement(Request $request, StudyGroup $studyGroup)
    {
        if (!$studyGroup->isModerator(auth()->user())) {
            abort(403, 'Only moderators can create announcements.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean',
        ]);

        $studyGroup->announcements()->create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Announcement posted!');
    }

    // Chat
    public function chat(StudyGroup $studyGroup): View
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        $messages = $studyGroup->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('study-groups.chat', compact('studyGroup', 'messages'));
    }

    public function storeMessage(Request $request, StudyGroup $studyGroup)
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $studyGroup->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        return back();
    }

    // Calendar (shows in personal calendar section)
    public function calendar(StudyGroup $studyGroup): View
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        return redirect()->route('calendar.index');
    }
}
