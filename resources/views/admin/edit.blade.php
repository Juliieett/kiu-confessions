@extends('layouts.app')

@section('title', 'Edit Confession #' . $confession->id)

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <a href="{{ route('admin.index') }}" class="back-link d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i> Back to Admin Panel
        </a>

        <div class="page-header">
            <h4><i class="bi bi-pencil text-primary me-2"></i>Edit Confession #{{ $confession->id }}</h4>
        </div>

        @include('partials.form-errors')

        <div class="card card-kiu">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.update', $confession) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="title">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $confession->title) }}"
                               maxlength="150" required />
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="category_id">Category</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $confession->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tags</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]"
                                       value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                                       {{ in_array($tag->id, old('tags', $confession->tags->pluck('id')->all())) ? 'checked' : '' }} />
                                <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('tags') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="image">Image</label>
                        @if($confession->imageUrl())
                        <div class="mb-2">
                            <img src="{{ $confession->imageUrl() }}" alt="Current image"
                                 class="img-fluid rounded" style="max-height: 200px;" />
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1"
                                   id="remove_image" {{ old('remove_image') ? 'checked' : '' }} />
                            <label class="form-check-label" for="remove_image">Remove current image</label>
                        </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" />
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Upload a new image to replace the current one. Max 2 MB.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="deadline">Review By Date (optional)</label>
                        <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                               id="deadline" name="deadline"
                               value="{{ old('deadline', $confession->deadline?->format('Y-m-d')) }}" />
                        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
