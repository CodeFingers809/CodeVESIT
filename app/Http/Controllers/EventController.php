<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        $pastEvents = Event::where('end_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->limit(5)
            ->get();

        return view('events.index', compact('upcomingEvents', 'pastEvents'));
    }

    public function create(): View
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        EventRequest::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Event request submitted for approval!');
    }

    public function show(Event $event): View
    {
        return view('events.show', compact('event'));
    }
}
