@if($confession->referencedConfession)
<div class="feed-post-reply mb-2">
    <i class="bi bi-reply-fill"></i>
    Replying to
    <a href="{{ route('confessions.show', $confession->referencedConfession) }}" class="fw-semibold">
        Post {{ $confession->referencedConfession->postNumber() }}
    </a>
</div>
@endif
