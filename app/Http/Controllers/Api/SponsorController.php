<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;

class SponsorController extends Controller
{
    // List sponsors with filtering by name.
    public function index(Request $request)
    {
        $query = Sponsor::with('events');

        // Filter by name (e.g., ?name=Acme)
        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $sponsors = $query->get();
        return response()->json($sponsors);
    }

    public function show($id)
    {
        $sponsor = Sponsor::with('events')->findOrFail($id);
        return response()->json($sponsor);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url'    => 'nullable|url',
        ]);

        $sponsor = Sponsor::create($validated);
        return response()->json($sponsor, 201);
    }

    public function update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'logo_url'    => 'sometimes|nullable|url',
        ]);

        $sponsor->update($validated);
        return response()->json($sponsor);
    }

    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->delete();
        return response()->json(['message' => 'Sponsor deleted successfully.']);
    }

    // Attach an event to this sponsor (demonstrates many-to-many).
    public function attachEvent(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $sponsor->events()->syncWithoutDetaching([$validated['event_id']]);
        return response()->json(['message' => 'Event attached to sponsor successfully.']);
    }
}
