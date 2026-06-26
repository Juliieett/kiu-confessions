<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Confession;
use App\Models\Like;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    private function visitorIpHash(Request $request): string
    {
        return hash('sha256', $request->ip());
    }

    public function index(Request $request)
    {
        $ipHash = $this->visitorIpHash($request);

        $query = Confession::approved()
            ->with(['category', 'tags', 'referencedConfession'])
            ->withCount(['likes', 'comments'])
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $confessions = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        $likedIds = auth()->check()
            ? Like::where('user_id', auth()->id())
                ->whereIn('confession_id', $confessions->pluck('id'))
                ->pluck('confession_id')
                ->all()
            : Like::whereNull('user_id')
                ->where('ip_hash', $ipHash)
                ->whereIn('confession_id', $confessions->pluck('id'))
                ->pluck('confession_id')
                ->all();

        return view('confessions.index', compact('confessions', 'categories', 'ipHash', 'likedIds'));
    }

    public function show(Request $request, Confession $confession)
    {
        abort_unless($confession->status === Confession::STATUS_APPROVED, 404);

        $ipHash = $this->visitorIpHash($request);

        $confession->load([
            'category',
            'tags',
            'referencedConfession',
            'comments' => fn ($q) => $q->latest(),
        ])->loadCount(['likes', 'comments']);

        $isLiked = $confession->isLikedBy(auth()->id(), auth()->check() ? null : $ipHash);

        return view('confessions.show', compact('confession', 'ipHash', 'isLiked'));
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $replyTo = $request->query('reply_to');

        return view('confessions.create', compact('categories', 'tags', 'replyTo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                    => 'required|string|max:150',
            'description'              => 'required|string|min:10|max:2000',
            'category_id'              => ['required', Rule::exists('categories', 'id')],
            'referenced_confession_id' => [
                'nullable',
                'integer',
                Rule::exists('confessions', 'id')->where('status', Confession::STATUS_APPROVED),
            ],
            'tags'         => 'nullable|array',
            'tags.*'       => [Rule::exists('tags', 'id')],
            'deadline'     => 'nullable|date|after_or_equal:today',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $validated['status']  = Confession::STATUS_PENDING;
        $validated['ip_hash'] = $this->visitorIpHash($request);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('confessions', 'public');
        }

        $confession = Confession::create($validated);
        $confession->tags()->sync($tagIds);

        return redirect()->route('confessions.index')
            ->with('success', '🎉 Your confession was submitted anonymously! It will appear after admin review.');
    }
}
