<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // List events with filtering by title and start_date range.
    public function index(Request $request)
    {
        $query = Event::with(['venue', 'manager', 'sponsors', 'attendees', 'comments']);

        // Filter by title
        if ($request->has('title')) {
            $query->where('title', 'like', '%'.$request->query('title').'%');
        }

        // Filter by start_date from (e.g., ?start_date_from=2025-01-01)
        if ($request->has('start_date_from')) {
            $query->where('start_date', '>=', $request->query('start_date_from'));
        }

        // Filter by start_date to (e.g., ?start_date_to=2025-12-31)
        if ($request->has('start_date_to')) {
            $query->where('start_date', '<=', $request->query('start_date_to'));
        }

        // You can add more filters as needed (e.g., by venue_id, status, etc.)

        $events = $query->get();
        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::with(['venue', 'manager', 'sponsors', 'attendees', 'comments'])->findOrFail($id);
        return response()->json($event);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'venue_id'    => 'required|exists:venues,id',
        ]);

        $event = Event::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'venue_id'    => $validated['venue_id'],
            'created_by'  => auth()->id(),
        ]);

        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        $event = auth()->user()->events()->find($id);
        if (!$event && auth()->user()->hasRole('admin')) {
            $event = Event::find($id);
        }
        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_date'  => 'sometimes|required|date',
            'end_date'    => 'sometimes|required|date|after_or_equal:start_date',
            'venue_id'    => 'sometimes|required|exists:venues,id',
        ]);

        $event->update($validated);
        return response()->json($event);
    }

    public function destroy($id)
    {

       $event = auth()->user()->events()->find($id);
        if (!$event && auth()->user()->hasRole('admin')) {
            $event = Event::find($id);
        }        
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully.']);
    }

    // Attach a sponsor to the event (demonstrates many-to-many).
    public function attachSponsor(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
        ]);

        $event->sponsors()->syncWithoutDetaching([$validated['sponsor_id']]);
        return response()->json(['message' => 'Sponsor attached successfully.']);
    }

    // List all attendees of the event.
    public function listAttendees($id)
    {
        $event =auth()->user()->events()->with('attendees')->findOrFail($id);
         if (!$event && auth()->user()->hasRole('admin')) {
            $event = Event::with('attendees')->findOrFail($id);
        }    

        return response()->json($event->attendees);
    }

    // Add a comment to the event using polymorphic relationship.
    public function addComment(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $event->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return response()->json($comment, 201);
    }
}
