<?php

namespace App\Http\Controllers;

use App\Models\Confession;
use Illuminate\Http\Request;

/**
 * AdminController
 *
 * Handles all admin-panel operations:
 *  - View all confessions with status filtering
 *  - Approve / Reject a confession
 *  - Edit a confession before approving
 *  - Delete a confession permanently
 *
 * Access is guarded by a simple secret key in .env (ADMIN_KEY).
 * No login system is required per the project spec.
 */
class AdminController extends Controller
{
    /**
     * Middleware-style key check applied to every admin action.
     * Reads ADMIN_KEY from .env (default: "admin123").
     */
    private function checkAdminKey(Request $request): void
    {
        $expectedKey = env('ADMIN_KEY', 'admin123');

        // Accept key from query param OR from session (set when first verified)
        if (
            $request->query('key') !== $expectedKey &&
            session('admin_verified') !== true
        ) {
            abort(403, 'Access denied. Append ?key=YOUR_ADMIN_KEY to the URL.');
        }

        // Store in session so admin doesn't need the key on every page
        session(['admin_verified' => true]);
    }

    // ─── READ: Admin Dashboard ─────────────────────────────────────────────────

    /**
     * Show all confessions with optional status filter.
     */
    public function index(Request $request)
    {
        $this->checkAdminKey($request);

        $query = Confession::latest();

        // Filter by status (pending / approved / rejected)
        $statusFilter = $request->get('status', 'all');
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Count for each tab badge
        $counts = [
            'all'      => Confession::count(),
            'pending'  => Confession::pending()->count(),
            'approved' => Confession::approved()->count(),
            'rejected' => Confession::rejected()->count(),
        ];

        $confessions = $query->paginate(15)->withQueryString();
        $categories  = Confession::categories();

        return view('admin.index', compact('confessions', 'counts', 'statusFilter', 'categories'));
    }

    // ─── UPDATE: Approve ───────────────────────────────────────────────────────

    /**
     * Mark a confession as approved (visible on public feed).
     */
    public function approve(Request $request, Confession $confession)
    {
        $this->checkAdminKey($request);

        $confession->update(['status' => Confession::STATUS_APPROVED]);

        return redirect()->back()
            ->with('success', "✅ Confession #{$confession->id} approved.");
    }

    // ─── UPDATE: Reject ────────────────────────────────────────────────────────

    /**
     * Mark a confession as rejected (hidden from public feed).
     */
    public function reject(Request $request, Confession $confession)
    {
        $this->checkAdminKey($request);

        $confession->update(['status' => Confession::STATUS_REJECTED]);

        return redirect()->back()
            ->with('success', "🚫 Confession #{$confession->id} rejected.");
    }

    // ─── UPDATE: Edit ──────────────────────────────────────────────────────────

    /**
     * Show edit form for a confession.
     */
    public function edit(Request $request, Confession $confession)
    {
        $this->checkAdminKey($request);

        $categories = Confession::categories();
        return view('admin.edit', compact('confession', 'categories'));
    }

    /**
     * Save admin edits to a confession.
     */
    public function update(Request $request, Confession $confession)
    {
        $this->checkAdminKey($request);

        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'required|string|min:10|max:2000',
            'category'    => 'required|string|in:' . implode(',', Confession::categories()),
            'status'      => 'required|in:pending,approved,rejected',
            'deadline'    => 'nullable|date',
        ]);

        $confession->update($validated);

        return redirect()->route('admin.index', ['key' => env('ADMIN_KEY', 'admin123')])
            ->with('success', "✏️ Confession #{$confession->id} updated.");
    }

    // ─── DELETE ────────────────────────────────────────────────────────────────

    /**
     * Permanently delete a confession.
     */
    public function destroy(Request $request, Confession $confession)
    {
        $this->checkAdminKey($request);

        $id = $confession->id;
        $confession->delete();

        return redirect()->back()
            ->with('success', "🗑️ Confession #{$id} deleted permanently.");
    }
}
