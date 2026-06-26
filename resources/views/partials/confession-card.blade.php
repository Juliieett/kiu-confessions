<div class="card card-kiu feed-post mb-4">
  <div class="feed-post-header">
    <div class="feed-post-avatar">
      <i class="bi bi-incognito"></i>
    </div>
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
    <h5 class="feed-post-title">
      <a href="{{ route('confessions.show', $confession) }}" class="text-decoration-none text-dark">
        {{ $confession->title }}
      </a>
    </h5>
    <p class="feed-post-text">{{ Str::limit($confession->description, 280) }}</p>
  </div>

  @if($confession->imageUrl())
  <a href="{{ route('confessions.show', $confession) }}" class="feed-post-image-wrap d-block">
    <img src="{{ $confession->imageUrl() }}" alt="" class="feed-post-image" />
  </a>
  @endif

  @include('partials.confession-actions', ['confession' => $confession])

  <div class="feed-post-footer border-top-0 pt-0">
    <a href="{{ route('confessions.show', $confession) }}" class="btn btn-light feed-post-action w-100">
      <i class="bi bi-chat-text me-2"></i> Read full confession
    </a>
  </div>
</div>
