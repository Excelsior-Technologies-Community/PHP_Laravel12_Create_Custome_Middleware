@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Admin Dashboard</h2>
            <small class="text-muted">User management & analytics overview</small>
        </div>
        <div>

            <a href="{{ route('admin.logs') }}"
                class="btn btn-dark btn-sm">
                Middleware Logs
            </a>


            <a href="{{ route('admin.statistics') }}"
                class="btn btn-dark btn-sm">
                Statistics
            </a>


            <a href="{{ route('dashboard') }}"
                class="btn btn-outline-secondary btn-sm">
                User Panel
            </a>

        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h3>{{ $totalUsers }}</h3>
                    <p class="mb-0">Total Users</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h3>{{ $activeUsers }}</h3>
                    <p class="mb-0">Active Users</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <h3>{{ $inactiveUsers }}</h3>
                    <p class="mb-0">Inactive Users</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body">
                    <h3>{{ $admins }}</h3>
                    <p class="mb-0">Admins</p>
                </div>
            </div>
        </div>

    </div>

    {{-- FILTER SECTION --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET" class="row g-2">

                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search name, email, role..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="moderator">Moderator</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary w-100">Search</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light w-100 border">Reset</a>
                </div>

            </form>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">User Activity Monitor</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Last Activity</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $user)

                        <tr>
                            <td>#{{ $user->id }}</td>

                            <td class="fw-semibold">{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'moderator' ? 'warning' : 'primary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <td>
                                {{ $user->last_activity_at ? $user->last_activity_at->diffForHumans() : 'Never' }}
                            </td>

                            <td>
                                @if($user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 5)
                                    <span class="badge bg-success">Online</span>
                                    @elseif($user->last_activity_at && $user->last_activity_at->diffInHours(now()) < 24)
                                        <span class="badge bg-warning text-dark">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No users found
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection