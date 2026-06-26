<?php

namespace App\Http\Controllers;

use App\Models\Confession;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like — per user account when logged in, per IP when guest.
     */
    public function toggle(Request $request, Confession $confession)
    {
        abort_unless($confession->status === Confession::STATUS_APPROVED, 404);

        if ($request->user()) {
            $existing = Like::where('confession_id', $confession->id)
                ->where('user_id', $request->user()->id)
                ->first();

            if ($existing) {
                $existing->delete();
                $message = 'Like removed.';
            } else {
                Like::create([
                    'confession_id' => $confession->id,
                    'user_id'       => $request->user()->id,
                ]);
                $message = 'Thanks for the like!';
            }
        } else {
            $ipHash = hash('sha256', $request->ip());

            $existing = Like::where('confession_id', $confession->id)
                ->whereNull('user_id')
                ->where('ip_hash', $ipHash)
                ->first();

            if ($existing) {
                $existing->delete();
                $message = 'Like removed.';
            } else {
                Like::create([
                    'confession_id' => $confession->id,
                    'ip_hash'       => $ipHash,
                ]);
                $message = 'Thanks for the like!';
            }
        }

        return redirect()->back()->with('success', $message);
    }
}
