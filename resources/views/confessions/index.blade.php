@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="feed-column">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 text-primary fw-bold">
        <i class="bi bi-chat-heart me-2"></i>Student Confessions
    </h4>
    <a href="{{ route('confessions.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Submit
    </a>
</div>

<div class="filter-bar d-flex flex-wrap align-items-center gap-3">
    <form method="GET" action="{{ route('confessions.index') }}" class="d-flex align-items-center gap-2">
        <label class="text-muted small mb-0"><i class="bi bi-funnel"></i> Category:</label>
        <select name="category_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @if(request('category_id'))
        <a href="{{ route('confessions.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        @endif
    </form>
    <span class="text-muted small ms-md-auto">
        <i class="bi bi-journal-text"></i> {{ $confessions->total() }} confession(s)
    </span>
</div>

@forelse($confessions as $confession)
    @include('partials.confession-card', ['confession' => $confession, 'likedIds' => $likedIds])
@empty
<div class="card card-kiu empty-state">
    <i class="bi bi-inbox d-block"></i>
    <h5 class="text-primary">No confessions yet</h5>
    <p class="mb-3">Be the first to share something with the KIU community.</p>
    <a href="{{ route('confessions.create') }}" class="btn btn-primary">
        <i class="bi bi-pencil-square me-1"></i> Submit the first confession
    </a>
</div>
@endforelse

@if($confessions->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $confessions->links() }}
</div>
@endif

</div>{{-- /.feed-column --}}

@endsection
