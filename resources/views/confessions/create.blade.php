@extends('layouts.app')

@section('title', 'Submit a Confession')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="page-header">
            <h4><i class="bi bi-pencil-square text-primary me-2"></i>Submit a Confession</h4>
            <p class="text-muted mb-0">Your identity is never stored. All confessions are reviewed before publishing.</p>
        </div>

        @include('partials.form-errors')

        <div class="card card-kiu">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('confessions.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g. I cried over a for-loop" maxlength="150" required />
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="referenced_confession_id">
                            <i class="bi bi-reply"></i> Reply to Post <span class="text-muted fw-normal">(optional)</span>
                        </label>
                        <input type="number" min="1" class="form-control @error('referenced_confession_id') is-invalid @enderror"
                               id="referenced_confession_id" name="referenced_confession_id"
                               value="{{ old('referenced_confession_id', $replyTo ?? '') }}"
                               placeholder="e.g. 5 — enter the post number you are responding to" />
                        @error('referenced_confession_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Use the post number (e.g. #5) from another confession to link your reply.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="category_id">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Choose...</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tags <span class="text-muted fw-normal">(optional)</span></label>
                        <div class="d-flex flex-wrap gap-3 p-3 rounded" style="background: var(--kiu-blue-soft);">
                            @foreach($tags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]"
                                       value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                                       {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }} />
                                <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('tags') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="description">Your Confession <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="6"
                                  placeholder="Write your confession here... (min 10 characters)"
                                  minlength="10" maxlength="2000" required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text text-end" id="charCount">0 / 2000</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="image">
                            <i class="bi bi-image"></i> Image <span class="text-muted fw-normal">(optional)</span>
                        </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" />
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">JPEG, PNG, or WebP. Max 2 MB.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="deadline">Review By Date <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                               id="deadline" name="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}" />
                        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-send me-1"></i> Submit Anonymously
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const textarea  = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    charCount.textContent = textarea.value.length + ' / 2000';
    textarea.addEventListener('input', function () {
        charCount.textContent = textarea.value.length + ' / 2000';
    });
</script>
@endpush

@endsection
