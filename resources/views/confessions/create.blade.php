@extends('layouts.app')

@section('title', 'Submit a Confession')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <h4 class="mb-1">Submit a Confession</h4>
        <p class="text-muted mb-4">Your identity is never stored. All confessions are reviewed before publishing.</p>

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
                <form method="POST" action="{{ route('confessions.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g. I cried over a for-loop" maxlength="150" required />
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="category">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>Choose...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="description">Your Confession <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="6"
                                  placeholder="Write your confession here... (min 10 characters)"
                                  minlength="10" maxlength="2000" required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text" id="charCount">0 / 2000</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="deadline">Review By Date <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                               id="deadline" name="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}" />
                        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Submit Anonymously</button>
                </form>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const textarea  = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    textarea.addEventListener('input', function () {
        charCount.textContent = textarea.value.length + ' / 2000';
    });
</script>
@endpush

@endsection
