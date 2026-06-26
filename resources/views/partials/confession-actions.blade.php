@php
    $isLiked = $isLiked ?? in_array($confession->id, $likedIds ?? []);
@endphp

<div class="feed-post-stats px-3 py-2 d-flex justify-content-between text-muted small">
    <span>
        @if($confession->likes_count > 0)
            <i class="bi bi-heart-fill text-danger"></i> {{ $confession->likes_count }}
        @endif
    </span>
    <span>
        @if($confession->comments_count > 0)
            {{ $confession->comments_count }} {{ Str::plural('comment', $confession->comments_count) }}
        @endif
    </span>
</div>

<div class="feed-post-actions d-flex border-top">
    <form method="POST" action="{{ route('confessions.like', $confession) }}" class="flex-fill">
        @csrf
        <button type="submit" class="feed-post-action-btn {{ $isLiked ? 'liked' : '' }}">
            <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }}"></i> Like
        </button>
    </form>
    <a href="{{ route('confessions.show', $confession) }}#comments" class="feed-post-action-btn flex-fill text-decoration-none">
        <i class="bi bi-chat"></i> Comment
    </a>
    <a href="{{ route('confessions.create', ['reply_to' => $confession->id]) }}"
       class="feed-post-action-btn flex-fill text-decoration-none">
        <i class="bi bi-reply"></i> Reply
    </a>
</div>
