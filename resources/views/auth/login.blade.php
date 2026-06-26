@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="auth-card">
    <div class="page-header text-center">
        <h4><i class="bi bi-box-arrow-in-right text-primary"></i> Login</h4>
        <p class="text-muted">Sign in to access the admin panel or your account.</p>
    </div>

    @include('partials.form-errors')

    <div class="card card-kiu">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="you@kiu.edu.ge" />
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required />
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" />
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                    Login
                </button>

                <p class="text-center text-muted mb-0 small">
                    No account? <a href="{{ route('register') }}" class="fw-semibold">Register here</a>
                </p>
            </form>
        </div>
    </div>
</div>

@endsection
