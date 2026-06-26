<span class="badge badge-category">{{ $confession->category?->name ?? ($fallback ?? 'Uncategorized') }}</span>
@foreach($confession->tags as $tag)
    <span class="badge tag-pill">{{ $tag->name }}</span>
@endforeach
