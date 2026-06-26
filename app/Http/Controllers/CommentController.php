<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Confession;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store an anonymous comment on an approved confession.
     */
    public function store(Request $request, Confession $confession)
    {
        abort_unless($confession->status === Confession::STATUS_APPROVED, 404);

        $validated = $request->validate([
            'body' => 'required|string|min:2|max:500',
        ]);

        Comment::create([
            'confession_id' => $confession->id,
            'body'          => $validated['body'],
            'ip_hash'       => hash('sha256', $request->ip()),
        ]);

        return redirect()
            ->route('confessions.show', $confession)
            ->with('success', 'Your comment was posted anonymously.');
    }
}
