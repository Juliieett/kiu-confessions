@extends('layouts.app')

@section('title', 'Edit Confession #' . $confession->id)

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <a href="{{ route('admin.index', ['key' => request('key')]) }}"
           class="text-secondary text-decoration-none d-inline-block mb-3">
            &larr; Back to Admin Panel
        </a>

        <h4 class="mb-4">Edit Confession #{{ $confession->id }}</h4>

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.update', $confession) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="key" value="{{ request('key') }}" />

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="title">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $confession->title) }}"
                               maxlength="150" required />
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="category">Category</label>
                        <select class="form-select @error('category') is-invalid @enderror" name="category" id="category">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $confession->category) === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="description">Confession Text</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="7"
                                  maxlength="2000" required>{{ old('description', $confession->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="status">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                            <option value="pending"  {{ old('status', $confession->status) === 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $confession->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $confession->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="deadline">Review By Date (optional)</label>
                        <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                               id="deadline" name="deadline"
                               value="{{ old('deadline', $confession->deadline?->format('Y-m-d')) }}" />
                        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Save Changes</button>
                        <a href="{{ route('admin.index', ['key' => request('key')]) }}"
                           class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
