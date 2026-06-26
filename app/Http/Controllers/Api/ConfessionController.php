<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConfessionResource;
use App\Models\Confession;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * API Resource Controller for confessions.
 *
 * Returns JSON responses for listing, viewing, and submitting confessions.
 */
class ConfessionController extends Controller
{
    /**
     * GET /api/confessions
     * List approved confessions (paginated).
     */
    public function index(Request $request)
    {
        $query = Confession::approved()
            ->with(['category', 'tags', 'referencedConfession'])
            ->withCount(['likes', 'comments'])
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $confessions = $query->paginate(10);

        return ConfessionResource::collection($confessions);
    }

    /**
     * GET /api/confessions/{confession}
     * Show a single approved confession.
     */
    public function show(Confession $confession)
    {
        abort_unless($confession->status === Confession::STATUS_APPROVED, 404);

        $confession->load(['category', 'tags', 'referencedConfession'])
            ->loadCount(['likes', 'comments']);

        return new ConfessionResource($confession);
    }

    /**
     * POST /api/confessions
     * Submit a new confession (starts as pending).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:150',
            'description'  => 'required|string|min:10|max:2000',
            'category_id'              => ['required', Rule::exists('categories', 'id')],
            'referenced_confession_id' => [
                'nullable',
                'integer',
                Rule::exists('confessions', 'id')->where('status', Confession::STATUS_APPROVED),
            ],
            'tags'         => 'nullable|array',
            'tags.*'       => [Rule::exists('tags', 'id')],
            'deadline'     => 'nullable|date|after_or_equal:today',
        ]);

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $validated['status']  = Confession::STATUS_PENDING;
        $validated['ip_hash'] = hash('sha256', $request->ip());

        $confession = Confession::create($validated);
        $confession->tags()->sync($tagIds);
        $confession->load(['category', 'tags']);

        return (new ConfessionResource($confession))
            ->response()
            ->setStatusCode(201);
    }
}
