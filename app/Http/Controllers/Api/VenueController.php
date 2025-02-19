<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;

class VenueController extends Controller
{
    // List venues with filtering by name.
    public function index(Request $request)
    {
        $query = Venue::with(['events', 'comments']);

        // Filter by name (e.g., ?name=Main)
        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $venues = $query->get();
        return response()->json($venues);
    }

    public function show($id)
    {
        $venue = Venue::with(['events', 'comments'])->findOrFail($id);
        return response()->json($venue);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'address'  => 'required|string',
            'capacity' => 'nullable|integer',
        ]);

        $venue = Venue::create($validated);
        return response()->json($venue, 201);
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'address'  => 'sometimes|required|string',
            'capacity' => 'sometimes|nullable|integer',
        ]);

        $venue->update($validated);
        return response()->json($venue);
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();
        return response()->json(['message' => 'Venue deleted successfully.']);
    }

    // Add a comment to the venue using polymorphic relationship.
    public function addComment(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $venue->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return response()->json($comment, 201);
    }

    // List all events at the venue.
    public function listEvents($id)
    {
        $venue = Venue::with('events')->findOrFail($id);
        return response()->json($venue->events);
    }
}
