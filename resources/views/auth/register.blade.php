@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="auth-card">
    <div class="page-header text-center">
        <h4><i class="bi bi-person-plus text-primary"></i> Register</h4>
        <p class="text-muted">Create an account to use KIU Confessions.</p>
    </div>

    @include('partials.form-errors')

    <div class="card card-kiu">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required autofocus />
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required />
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required />
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation" required />
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                    Create Account
                </button>

                <p class="text-center text-muted mb-0 small">
                    Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Login here</a>
                </p>
            </form>
        </div>
    </div>
</div>

@endsection
