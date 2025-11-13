<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\StudyGroupTodo;
use App\Models\StudyGroupAnnouncement;
use App\Models\StudyGroupMessage;
use App\Models\StudyGroupCalendarEvent;
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

    public function show(StudyGroup $studyGroup)
    {
        if (!$studyGroup->isApproved()) {
            abort(404, 'Study group not found or not approved.');
        }

        if (!$studyGroup->isMember(auth()->user())) {
            abort(403, 'You must be a member to view this study group.');
        }

        // Redirect to announcements (default view)
        return redirect()->route('study-groups.announcements', $studyGroup);
    }

    public function join(Request $request)
    {
        $validated = $request->validate([
            'join_code' => 'required|string|size:8',
        ]);

        $studyGroup = StudyGroup::where('join_code', strtoupper($validated['join_code']))->first();

        if (!$studyGroup) {
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
            ->with('creator')
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

        $announcement = $studyGroup->announcements()->create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        // Send email notifications to all group members except the creator
        foreach ($studyGroup->members as $member) {
            if ($member->id !== auth()->id()) {
                $member->notify(new \App\Notifications\AnnouncementCreated($announcement));
            }
        }

        return back()->with('success', 'Announcement posted!');
    }

    public function destroyAnnouncement(StudyGroup $studyGroup, StudyGroupAnnouncement $announcement)
    {
        if (!$studyGroup->isModerator(auth()->user()) || $announcement->created_by !== auth()->id()) {
            abort(403, 'Only the creator can delete this announcement.');
        }

        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully!');
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

    public function reportMessage(Request $request, StudyGroup $studyGroup, $messageId)
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        $message = $studyGroup->messages()->findOrFail($messageId);

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        \App\Models\Report::create([
            'user_id' => auth()->id(),
            'reportable_type' => \App\Models\StudyGroupMessage::class,
            'reportable_id' => $message->id,
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Message reported successfully!');
    }

    // Calendar
    public function calendar(StudyGroup $studyGroup): View
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        // Auto-complete events that have passed their date
        $studyGroup->calendarEvents()
            ->where('is_completed', false)
            ->where('event_date', '<', now())
            ->update(['is_completed' => true]);

        $events = $studyGroup->calendarEvents()
            ->with('creator')
            ->orderBy('event_date', 'asc')
            ->get();
        $isModerator = $studyGroup->isModerator(auth()->user());

        return view('study-groups.calendar', compact('studyGroup', 'events', 'isModerator'));
    }

    public function storeCalendarEvent(Request $request, StudyGroup $studyGroup)
    {
        if (!$studyGroup->isModerator(auth()->user())) {
            abort(403, 'Only moderators can create calendar events.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $studyGroup->calendarEvents()->create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Calendar event created successfully!');
    }

    public function updateCalendarEvent(Request $request, StudyGroupCalendarEvent $event)
    {
        if (!$event->studyGroup->isModerator(auth()->user())) {
            abort(403, 'Only moderators can edit calendar events.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $event->update($validated);

        return back()->with('success', 'Calendar event updated successfully!');
    }

    public function toggleCalendarEvent(StudyGroupCalendarEvent $event)
    {
        if (!$event->studyGroup->isModerator(auth()->user())) {
            abort(403, 'Only moderators can manually toggle event completion.');
        }

        $event->update([
            'is_completed' => !$event->is_completed,
        ]);

        return back()->with('success', 'Event status updated!');
    }

    public function destroyCalendarEvent(StudyGroupCalendarEvent $event)
    {
        if (!$event->studyGroup->isModerator(auth()->user())) {
            abort(403, 'Only moderators can delete calendar events.');
        }

        $event->delete();

        return back()->with('success', 'Calendar event deleted successfully!');
    }

    // Settings (moderators only)
    public function settings(StudyGroup $studyGroup): View
    {
        if (!$studyGroup->isMember(auth()->user())) {
            abort(403);
        }

        $isModerator = $studyGroup->isModerator(auth()->user());
        $isCreator = $studyGroup->created_by === auth()->id();
        $members = $studyGroup->members()->withPivot('created_at')->get();
        $moderators = $studyGroup->moderators()->get();

        return view('study-groups.settings', compact('studyGroup', 'isModerator', 'isCreator', 'members', 'moderators'));
    }

    public function addModerator(Request $request, StudyGroup $studyGroup)
    {
        if ($studyGroup->created_by !== auth()->id()) {
            abort(403, 'Only the group creator can add moderators.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $validated['user_id'];

        // Check if user is a member
        if (!$studyGroup->isMember(\App\Models\User::find($userId))) {
            return back()->withErrors(['user_id' => 'User must be a member of the group first.']);
        }

        // Check if already a moderator
        if ($studyGroup->isModerator(\App\Models\User::find($userId))) {
            return back()->withErrors(['user_id' => 'User is already a moderator.']);
        }

        $studyGroup->moderators()->attach($userId, ['assigned_by' => auth()->id()]);

        return back()->with('success', 'Moderator added successfully!');
    }

    public function removeModerator(Request $request, StudyGroup $studyGroup, $userId)
    {
        if ($studyGroup->created_by !== auth()->id()) {
            abort(403, 'Only the group creator can remove moderators.');
        }

        if ($studyGroup->created_by == $userId) {
            return back()->withErrors(['error' => 'Cannot remove creator as moderator.']);
        }

        $studyGroup->moderators()->detach($userId);

        return back()->with('success', 'Moderator removed successfully!');
    }
}
