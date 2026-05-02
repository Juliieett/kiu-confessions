@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Admin Panel</h4>
        <small class="text-muted">Moderate confessions — approve, reject, edit, or delete.</small>
    </div>
    <a href="{{ route('confessions.index') }}" class="btn btn-sm btn-outline-secondary">View Public Feed</a>
</div>

{{-- Status tabs --}}
<ul class="nav nav-tabs mb-3">
    @php
        $tabs = ['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];
    @endphp
    @foreach($tabs as $key => $label)
    <li class="nav-item">
        <a class="nav-link {{ $statusFilter === $key ? 'active' : '' }}"
           href="{{ route('admin.index', ['key' => request('key'), 'status' => $key]) }}">
            {{ $label }}
            <span class="badge bg-secondary ms-1">{{ $counts[$key] }}</span>
        </a>
    </li>
    @endforeach
</ul>

{{-- Category filter --}}
<form method="GET" action="{{ route('admin.index') }}" class="d-flex gap-2 mb-3">
    <input type="hidden" name="key" value="{{ request('key') }}" />
    <input type="hidden" name="status" value="{{ $statusFilter }}" />
    <select name="category" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
    </select>
    @if(request('category'))
    <a href="{{ route('admin.index', ['key' => request('key'), 'status' => $statusFilter]) }}"
       class="btn btn-sm btn-outline-secondary">Clear</a>
    @endif
</form>

{{-- Table --}}
<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
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
            <td>{{ $confession->id }}</td>
            <td>
                <div>{{ $confession->title }}</div>
                <small class="text-muted">{{ Str::limit($confession->description, 80) }}</small>
            </td>
            <td><span class="badge bg-secondary">{{ $confession->category }}</span></td>
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
            <td><small>{{ $confession->created_at->format('M d, Y') }}</small></td>
            <td>
                <div class="d-flex gap-1 flex-wrap">
                    {{-- Approve --}}
                    @if($confession->status !== 'approved')
                    <form method="POST" action="{{ route('admin.approve', $confession) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="key" value="{{ request('key') }}" />
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                    @endif

                    {{-- Reject --}}
                    @if($confession->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.reject', $confession) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="key" value="{{ request('key') }}" />
                        <button class="btn btn-sm btn-warning">Reject</button>
                    </form>
                    @endif

                    {{-- Edit --}}
                    <a href="{{ route('admin.edit', [$confession, 'key' => request('key')]) }}"
                       class="btn btn-sm btn-outline-secondary">Edit</a>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.destroy', $confession) }}"
                          onsubmit="return confirm('Delete this confession permanently?')">
                        @csrf @method('DELETE')
                        <input type="hidden" name="key" value="{{ request('key') }}" />
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted py-4">No confessions found.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($confessions->hasPages())
<div class="d-flex justify-content-center mt-3">
    {{ $confessions->appends(request()->except('page'))->links() }}
</div>
@endif

@endsection
