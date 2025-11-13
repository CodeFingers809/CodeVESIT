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
        $events = auth()->user()->calendarEvents()->orderBy('start_date')->get();

        return view('calendar.index', compact('events'));
    }

    public function storeCalendarEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:task,event,reminder',
        ]);

        auth()->user()->calendarEvents()->create($validated);

        return redirect()->back()->with('success', 'Event created successfully!');
    }

    public function updateCalendarEvent(Request $request, $event)
    {
        $calendarEvent = auth()->user()->calendarEvents()->findOrFail($event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:task,event,reminder',
            'is_completed' => 'boolean',
        ]);

        $calendarEvent->update($validated);

        return redirect()->back()->with('success', 'Event updated successfully!');
    }

    public function destroyCalendarEvent($event)
    {
        $calendarEvent = auth()->user()->calendarEvents()->findOrFail($event);
        $calendarEvent->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}
