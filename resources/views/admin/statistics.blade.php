<!-- resources/views/admin/statistics.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3>System Statistics</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h1 class="display-4">{{ $totalUsers }}</h1>
                                        <h6>Total Users</h6>
                                    </div>
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h1 class="display-4">{{ $activeUsers }}</h1>
                                        <h6>Active Today</h6>
                                    </div>
                                    <i class="fas fa-user-check fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h1 class="display-4">{{ $admins }}</h1>
                                        <h6>Admin Users</h6>
                                    </div>
                                    <i class="fas fa-user-shield fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @php
                                            $inactiveUsers = $totalUsers - $activeUsers;
                                        @endphp
                                        <h1 class="display-4">{{ $inactiveUsers }}</h1>
                                        <h6>Inactive Users</h6>
                                    </div>
                                    <i class="fas fa-user-clock fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>User Role Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="roleChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Activity Overview</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Online (Last 5 minutes)
                                        <span class="badge bg-success rounded-pill">
                                            {{ App\Models\User::where('last_activity_at', '>=', now()->subMinutes(5))->count() }}
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Active Today
                                        <span class="badge bg-primary rounded-pill">{{ $activeUsers }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Active This Week
                                        <span class="badge bg-info rounded-pill">
                                            {{ App\Models\User::where('last_activity_at', '>=', now()->subWeek())->count() }}
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Inactive (>30 days)
                                        <span class="badge bg-danger rounded-pill">
                                            {{ App\Models\User::where('last_activity_at', '<=', now()->subDays(30))->orWhereNull('last_activity_at')->count() }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Admin Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('roleChart').getContext('2d');
        const roleChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Admins', 'Moderators', 'Regular Users'],
                datasets: [{
                    data: [
                        {{ App\Models\User::where('role', 'admin')->count() }},
                        {{ App\Models\User::where('role', 'moderator')->count() }},
                        {{ App\Models\User::where('role', 'user')->count() }}
                    ],
                    backgroundColor: [
                        '#dc3545',
                        '#ffc107',
                        '#0d6efd'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection