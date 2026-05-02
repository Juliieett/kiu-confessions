@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Student Confessions</h4>
    <a href="{{ route('confessions.create') }}" class="btn btn-dark">+ Submit Confession</a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('confessions.index') }}" class="row g-2 align-items-center mb-4">
    <div class="col-auto">
        <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
    </div>
    @if(request('category'))
    <div class="col-auto">
        <a href="{{ route('confessions.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
    </div>
    @endif
    <div class="col text-end text-muted small">
        {{ $confessions->total() }} confession(s)
    </div>
</form>

{{-- List --}}
@forelse($confessions as $confession)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="card-title mb-1">
                    <a href="{{ route('confessions.show', $confession) }}" class="text-dark text-decoration-none">
                        {{ $confession->title }}
                    </a>
                </h6>
                <span class="badge bg-secondary">{{ $confession->category }}</span>
            </div>
            <small class="text-muted">{{ $confession->created_at->diffForHumans() }}</small>
        </div>
        <p class="card-text text-muted mt-2 mb-2" style="font-size: 0.9rem;">
            {{ Str::limit($confession->description, 200) }}
        </p>
        <a href="{{ route('confessions.show', $confession) }}" class="btn btn-sm btn-outline-dark">Read more</a>
    </div>
</div>
@empty
<div class="text-center text-muted py-5">
    <p>No confessions yet.</p>
    <a href="{{ route('confessions.create') }}" class="btn btn-dark">Be the first to submit</a>
</div>
@endforelse

{{-- Pagination --}}
@if($confessions->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $confessions->links() }}
</div>
@endif

@endsection
