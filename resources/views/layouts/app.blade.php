<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Middleware Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add this in the head section of resources/views/layouts/app.blade.php -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fa-solid fa-shield-cat text-primary me-2"></i>Middleware Project
            </a>
            @auth
                <span class="navbar-text text-white mx-2 small opacity-75">
                    Role: <span class="badge bg-warning text-dark">{{ ucfirst(auth()->user()->role) }}</span> | 
                    Last active: {{ auth()->user()->last_activity_at ? auth()->user()->last_activity_at->diffForHumans() : 'Never' }}
                </span>
            @endauth
            <div class="navbar-nav ms-auto gap-2">
                <a class="nav-link fw-semibold text-warning" href="{{ route('sandbox.index') }}">
                    <i class="fa-solid fa-vial me-1"></i> Middleware Sandbox
                </a>
                @auth
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    @if(auth()->user()->role === 'admin')
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">Logout</button>
                    </form>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>