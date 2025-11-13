<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('dashboard', [
            'user' => $user,
        ]);
    }

    public function calendar(): View
    {
        $upcomingEvents = auth()->user()->calendarEvents()
            ->where('is_completed', false)
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->get();

        $completedEvents = auth()->user()->calendarEvents()
            ->where('is_completed', true)
            ->orderBy('event_date', 'desc')
            ->limit(10)
            ->get();

        return view('calendar.index', compact('upcomingEvents', 'completedEvents'));
    }

    public function storeCalendarEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        auth()->user()->calendarEvents()->create($validated);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function updateCalendarEvent(Request $request, $event)
    {
        $calendarEvent = auth()->user()->calendarEvents()->findOrFail($event);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'is_completed' => 'nullable|boolean',
        ]);

        $calendarEvent->update(array_filter($validated));

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    public function destroyCalendarEvent($event)
    {
        $calendarEvent = auth()->user()->calendarEvents()->findOrFail($event);
        $calendarEvent->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}
