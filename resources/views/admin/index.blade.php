@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div class="page-header mb-0">
        <h4><i class="bi bi-shield-check text-primary me-2"></i>Admin Panel</h4>
        <p class="text-muted mb-0">Moderate confessions — approve, reject, edit, or delete.</p>
    </div>
    <a href="{{ route('confessions.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-eye me-1"></i> View Public Feed
    </a>
</div>

<ul class="nav nav-tabs nav-tabs-kiu mb-4">
    @php
        $tabs = ['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];
    @endphp
    @foreach($tabs as $key => $label)
    <li class="nav-item">
        <a class="nav-link {{ $statusFilter === $key ? 'active' : '' }}"
           href="{{ route('admin.index', ['status' => $key]) }}">
            {{ $label }}
            <span class="badge {{ $statusFilter === $key ? 'bg-primary' : 'bg-secondary' }} ms-1">
                {{ $counts[$key] }}
            </span>
        </a>
    </li>
    @endforeach
</ul>

<div class="filter-bar d-flex flex-wrap align-items-center gap-2 mb-4">
    <form method="GET" action="{{ route('admin.index') }}" class="d-flex align-items-center gap-2">
        <input type="hidden" name="status" value="{{ $statusFilter }}" />
        <label class="text-muted small mb-0"><i class="bi bi-funnel"></i></label>
        <select name="category_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        @if(request('category_id'))
        <a href="{{ route('admin.index', ['status' => $statusFilter]) }}"
           class="btn btn-sm btn-outline-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-kiu table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Post</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Deadline</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($confessions as $confession)
        <tr>
            <td class="text-muted small">{{ $confession->id }}</td>
            <td><span class="post-number-badge">{{ $confession->postNumber() }}</span></td>
            <td>
                <div class="fw-semibold">{{ $confession->title }}</div>
                <small class="text-muted">{{ Str::limit($confession->description, 80) }}</small>
                @if($confession->referencedConfession)
                <small class="d-block text-primary">↳ Reply to {{ $confession->referencedConfession->postNumber() }}</small>
                @endif
                <small class="d-block text-muted">
                    <i class="bi bi-heart"></i> {{ $confession->likes_count }}
                    &middot; <i class="bi bi-chat"></i> {{ $confession->comments_count }}
                </small>
            </td>
            <td>
                @include('partials.confession-badges', ['confession' => $confession, 'fallback' => '—'])
            </td>
            <td>
                <span class="badge {{ $confession->statusBadgeClass() }}">
                    {{ $confession->statusLabel() }}
                </span>
            </td>
            <td>
                @if($confession->deadline)
                    {{ $confession->deadline->format('M d, Y') }}
                    @if($confession->deadline->isPast())
                        <span class="text-danger small">(overdue)</span>
                    @endif
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td><small class="text-muted">{{ $confession->created_at->format('M d, Y') }}</small></td>
            <td>
                <div class="d-flex gap-1 flex-wrap">
                    @if($confession->status !== 'approved')
                    <form method="POST" action="{{ route('admin.approve', $confession) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-success" title="Approve">
                            <i class="bi bi-check-lg"></i>
                        </button>
                    </form>
                    @endif

                    @if($confession->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.reject', $confession) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-warning" title="Reject">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.edit', $confession) }}"
                       class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <form method="POST" action="{{ route('admin.destroy', $confession) }}"
                          onsubmit="return confirm('Delete this confession permanently?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                No confessions found.
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($confessions->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $confessions->appends(request()->except('page'))->links() }}
</div>
@endif

@endsection
