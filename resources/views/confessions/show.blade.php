@extends('layouts.app')

@section('title', Str::limit($confession->title, 50))

@section('content')

<div class="feed-column">

    <a href="{{ route('confessions.index') }}" class="back-link d-inline-flex align-items-center gap-1 mb-3">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <div class="card card-kiu feed-post mb-3">
        <div class="feed-post-header">
            <div class="feed-post-avatar"><i class="bi bi-incognito"></i></div>
            <div class="feed-post-meta flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="feed-post-author">Anonymous Student</div>
                    <span class="post-number-badge">{{ $confession->postNumber() }}</span>
                </div>
                <div class="feed-post-time">
                    @include('partials.confession-badges', ['confession' => $confession])
                    <span class="text-muted ms-1">&middot; {{ $confession->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <div class="feed-post-body">
            @include('partials.confession-reference', ['confession' => $confession])
            <h4 class="fw-bold text-primary mb-3">{{ $confession->title }}</h4>

            @if($confession->imageUrl())
            <img src="{{ $confession->imageUrl() }}" alt="Confession image" class="feed-post-image mb-3" />
            @endif

            <p class="mb-0" style="white-space: pre-wrap; line-height: 1.8; font-size: 1.05rem;">
                {{ $confession->description }}
            </p>
        </div>

        @include('partials.confession-actions', [
            'confession' => $confession,
            'isLiked' => $isLiked,
            'likedIds' => [],
        ])
    </div>

    {{-- Comments --}}
    <div class="card card-kiu" id="comments">
        <div class="card-body p-4">
            <h5 class="fw-bold text-primary mb-3">
                <i class="bi bi-chat-dots me-2"></i>Comments ({{ $confession->comments->count() }})
            </h5>

            <form method="POST" action="{{ route('confessions.comments.store', $confession) }}" class="mb-4">
                @csrf
                <div class="mb-2">
                    <textarea name="body" rows="3" class="form-control @error('body') is-invalid @enderror"
                              placeholder="Write an anonymous comment... (reference Post {{ $confession->postNumber() }} in your reply)"
                              maxlength="500" required>{{ old('body') }}</textarea>
                    @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-send me-1"></i> Post Comment
                </button>
            </form>

            @forelse($confession->comments as $comment)
            <div class="comment-item">
                <div class="d-flex gap-2">
                    <div class="comment-avatar"><i class="bi bi-person"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">Anonymous</div>
                        <p class="mb-1">{{ $comment->body }}</p>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted mb-0">No comments yet. Be the first to respond.</p>
            @endforelse
        </div>
    </div>

    <div class="text-center mt-4 mb-2">
        <a href="{{ route('confessions.create', ['reply_to' => $confession->id]) }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-reply me-1"></i> Reply as Confession
        </a>
        <a href="{{ route('confessions.create') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> New Confession
        </a>
    </div>

</div>

@endsection
