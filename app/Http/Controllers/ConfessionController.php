<?php

namespace App\Http\Controllers;

use App\Models\Confession;
use Illuminate\Http\Request;

/**
 * ConfessionController
 *
 * Handles the public-facing side of the KIU Confessions site:
 *  - Browse approved confessions (with category filter)
 *  - View a single confession
 *  - Submit a new anonymous confession
 */
class ConfessionController extends Controller
{
    // ─── READ: List Approved Confessions ──────────────────────────────────────

    /**
     * Display the public confession feed.
     * Supports filtering by category and status toggle (approved by default).
     */
    public function index(Request $request)
    {
        // Start with approved confessions only
        $query = Confession::approved()->latest();

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Paginate: 10 per page
        $confessions = $query->paginate(10)->withQueryString();

        // Pass all category options for the filter dropdown
        $categories = Confession::categories();

        return view('confessions.index', compact('confessions', 'categories'));
    }

    // ─── READ: Single Confession ───────────────────────────────────────────────

    /**
     * Display a single approved confession.
     */
    public function show(Confession $confession)
    {
        // Only show approved confessions publicly; others return 404
        abort_unless($confession->status === Confession::STATUS_APPROVED, 404);

        return view('confessions.show', compact('confession'));
    }

    // ─── CREATE: Submission Form ───────────────────────────────────────────────

    /**
     * Show the anonymous confession submission form.
     */
    public function create()
    {
        $categories = Confession::categories();
        return view('confessions.create', compact('categories'));
    }

    /**
     * Store a newly submitted confession.
     * All confessions start as "pending" – admin must approve.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string|min:10|max:2000',
            'category'    => 'required|string|in:' . implode(',', Confession::categories()),
            'deadline'    => 'nullable|date|after_or_equal:today',
        ]);

        // All new submissions are pending by default
        $validated['status']  = Confession::STATUS_PENDING;
        // Store hashed IP to help admin spot spam (never stored in plain text)
        $validated['ip_hash'] = hash('sha256', $request->ip());

        Confession::create($validated);

        // Redirect back with a success flash message
        return redirect()->route('confessions.index')
            ->with('success', '🎉 Your confession was submitted anonymously! It will appear after admin review.');
    }
}
