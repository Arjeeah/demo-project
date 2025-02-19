<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    // List comments with filtering by commentable_type.
    public function index(Request $request)
    {
        $query = Comment::with(['commentable', 'user']);

        // Filter by commentable_type (e.g., ?commentable_type=App\Models\Event)
        if ($request->has('commentable_type')) {
            $query->where('commentable_type', $request->query('commentable_type'));
        }

        // Optionally, filter by commentable_id.
        if ($request->has('commentable_id')) {
            $query->where('commentable_id', $request->query('commentable_id'));
        }

        $comments = $query->get();
        return response()->json($comments);
    }

    public function show($id)
    {
        $comment = Comment::with(['commentable', 'user'])->findOrFail($id);
        return response()->json($comment);
    }

    // Create a new comment by specifying the polymorphic fields.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'          => 'required|string',
            'commentable_type' => 'required|string',
            'commentable_id'   => 'required|integer',
        ]);

        $comment = Comment::create([
            'user_id'          => auth()->id(),
            'commentable_type' => $validated['commentable_type'],
            'commentable_id'   => $validated['commentable_id'],
            'content'          => $validated['content'],
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = auth()->user()->comments()->find($id);

        if (!$comment && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('event_manager'))) {
            $comment = Comment::findOrFail($id);
        } else if (!$comment) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        $comment->update($validated);
        return response()->json($comment);
    }

    public function destroy($id)
    {

        $comment = auth()->user()->comments()->find($id);

        if (!$comment && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('event_manager'))) {
            $comment = Comment::findOrFail($id);
        } else if (!$comment) {
            abort(403, 'Unauthorized action.');
        }       
         $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
