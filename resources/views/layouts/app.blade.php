<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'KIU Confessions') — Kutaisi International University</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-kiu">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('confessions.index') }}">
            <i class="bi bi-chat-heart-fill"></i>
            KIU Confessions
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('confessions.index') ? 'active' : '' }}"
                       href="{{ route('confessions.index') }}">
                        <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('confessions.create') ? 'active' : '' }}"
                       href="{{ route('confessions.create') }}">
                        <i class="bi bi-pencil-square me-1"></i> Submit
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center gap-2">
                @auth
                    @if(auth()->user()->is_admin)
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                           href="{{ route('admin.index') }}">
                            <i class="bi bi-shield-check me-1"></i> Admin
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <span class="nav-link text-light py-0 small">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-light" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">

    @include('partials.flash')

    @yield('content')

</main>

<footer class="footer-kiu text-center py-4 mt-auto">
    <div class="container">
        <small>
            <i class="bi bi-mortarboard text-primary"></i>
            KIU Confessions &mdash; Anonymous Student Platform &mdash; Kutaisi International University
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
