@extends('layouts.app')

@section('title', Str::limit($confession->title, 50))

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <a href="{{ route('confessions.index') }}" class="text-secondary text-decoration-none d-inline-block mb-3">
            &larr; Back to Home
        </a>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-secondary">{{ $confession->category }}</span>
                    <small class="text-muted">{{ $confession->created_at->diffForHumans() }}</small>
                </div>

                <h5 class="card-title">{{ $confession->title }}</h5>
                <hr />
                <p class="card-text" style="white-space: pre-wrap; line-height: 1.7;">{{ $confession->description }}</p>
                <hr />
                <small class="text-muted">
                    Posted anonymously
                    @if($confession->deadline)
                        &middot; Review by {{ $confession->deadline->format('M d, Y') }}
                    @endif
                </small>
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('confessions.create') }}" class="btn btn-dark">Submit Your Own Confession</a>
        </div>

    </div>
</div>

@endsection
