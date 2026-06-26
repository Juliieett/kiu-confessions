<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Confession;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * AdminController
 *
 * Handles all admin-panel operations:
 *  - View all confessions with status filtering
 *  - Approve / Reject a confession
 *  - Edit a confession before approving
 *  - Delete a confession permanently
 *
 * Access is guarded by auth + admin middleware.
 */
class AdminController extends Controller
{
    /**
     * Show all confessions with optional status filter.
     */
    public function index(Request $request)
    {
        $query = Confession::with(['category', 'tags', 'referencedConfession'])
            ->withCount(['likes', 'comments'])
            ->latest();

        $statusFilter = $request->get('status', 'all');
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $counts = [
            'all'      => Confession::count(),
            'pending'  => Confession::pending()->count(),
            'approved' => Confession::approved()->count(),
            'rejected' => Confession::rejected()->count(),
        ];

        $confessions = $query->paginate(15)->withQueryString();
        $categories  = Category::orderBy('name')->get();

        return view('admin.index', compact('confessions', 'counts', 'statusFilter', 'categories'));
    }

    /**
     * Mark a confession as approved (visible on public feed).
     */
    public function approve(Confession $confession)
    {
        $confession->update(['status' => Confession::STATUS_APPROVED]);

        return redirect()->back()
            ->with('success', "✅ Confession #{$confession->id} approved.");
    }

    /**
     * Mark a confession as rejected (hidden from public feed).
     */
    public function reject(Confession $confession)
    {
        $confession->update(['status' => Confession::STATUS_REJECTED]);

        return redirect()->back()
            ->with('success', "🚫 Confession #{$confession->id} rejected.");
    }

    /**
     * Show edit form for a confession.
     */
    public function edit(Confession $confession)
    {
        $confession->load(['category', 'tags']);
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.edit', compact('confession', 'categories', 'tags'));
    }

    /**
     * Save admin edits to a confession.
     */
    public function update(Request $request, Confession $confession)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:150',
            'description'  => 'required|string|min:10|max:2000',
            'category_id'              => ['required', Rule::exists('categories', 'id')],
            'referenced_confession_id' => [
                'nullable',
                'integer',
                Rule::exists('confessions', 'id'),
            ],
            'tags'         => 'nullable|array',
            'tags.*'       => [Rule::exists('tags', 'id')],
            'status'       => 'required|in:pending,approved,rejected',
            'deadline'     => 'nullable|date',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags'], $validated['remove_image']);

        if ($request->boolean('remove_image')) {
            $confession->deleteImage();
            $validated['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            $confession->deleteImage();
            $validated['image_path'] = $request->file('image')->store('confessions', 'public');
        }

        $confession->update($validated);
        $confession->tags()->sync($tagIds);

        return redirect()->route('admin.index')
            ->with('success', "✏️ Confession #{$confession->id} updated.");
    }

    /**
     * Permanently delete a confession.
     */
    public function destroy(Confession $confession)
    {
        $id = $confession->id;
        $confession->deleteImage();
        $confession->delete();

        return redirect()->back()
            ->with('success', "You deleted Post #{$id} successfully.");
    }
}
