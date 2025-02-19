<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    // List tickets with filtering by event_id and seat_number.
    public function index(Request $request)
    {
        $query = Ticket::with(['event', 'user']);

        // Filter by event_id (e.g., ?event_id=5)
        if ($request->has('event_id')) {
            $query->where('event_id', $request->query('event_id'));
        }

        // Filter by seat_number (partial match)
        if ($request->has('seat_number')) {
            $query->where('seat_number', 'like', '%'.$request->query('seat_number').'%');
        }

        $tickets = $query->get();
        return response()->json($tickets);
    }

    public function show($id)
    {
        $ticket = Ticket::with(['event', 'user'])->findOrFail($id);
        return response()->json($ticket);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id'    => 'required|exists:events,id',
            'seat_number' => 'required|string',
        ]);

        // Check for race condition
        $existingTicket = Ticket::where('event_id', $validated['event_id'])
                    ->where('seat_number', $validated['seat_number'])
                    ->first();

        if ($existingTicket) {
            return response()->json(['message' => 'This seat is already taken.'], status: 409);
        }

        $ticket = Ticket::create([
            'event_id'      => $validated['event_id'],
            'user_id'       => auth()->id(),
            'purchase_date' => now(),
            'seat_number'   => $validated['seat_number'],
        ]);

        return response()->json($ticket, 201);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validate([
            'seat_number' => 'sometimes|required|string',
        ]);
        $ticket->update($validated);
        return response()->json($ticket);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted successfully.']);
    }
}
