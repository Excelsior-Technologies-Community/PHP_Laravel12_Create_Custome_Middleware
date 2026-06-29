@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <div class="card shadow">

            <div class="card-header bg-info text-white">
                <h3 class="mb-0">
                    📊 System Statistics Dashboard
                </h3>
            </div>

            <div class="card-body">

                <!-- Statistics Cards -->

                <div class="row">

                    <div class="col-md-3 mb-4">

                        <div class="card bg-primary text-white shadow">

                            <div class="card-body text-center">

                                <h1>{{ $totalUsers }}</h1>

                                <h5>Total Users</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3 mb-4">

                        <div class="card bg-success text-white shadow">

                            <div class="card-body text-center">

                                <h1>{{ $activeUsers }}</h1>

                                <h5>Active Users</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3 mb-4">

                        <div class="card bg-danger text-white shadow">

                            <div class="card-body text-center">

                                <h1>{{ $admins }}</h1>

                                <h5>Admins</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3 mb-4">

                        <div class="card bg-warning shadow">

                            <div class="card-body text-center">

                                <h1>{{ $inactiveUsers }}</h1>

                                <h5>Inactive Users</h5>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- End Statistics Cards -->

                <!-- Charts Row -->

                <div class="row mt-4">

                    <div class="col-md-6">

                        <div class="card shadow">

                            <div class="card-header bg-primary text-white">

                                <h5 class="mb-0">
                                    User Role Distribution
                                </h5>

                            </div>

                            <div class="card-body">

                                <canvas id="roleChart"></canvas>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card shadow">

                            <div class="card-header bg-success text-white">

                                <h5 class="mb-0">
                                    Activity Overview
                                </h5>

                            </div>

                            <div class="card-body">

                                <ul class="list-group">

                                    <li class="list-group-item d-flex justify-content-between">

                                        Online Users

                                        <span class="badge bg-success">

                                            {{ $onlineUsers }}

                                        </span>

                                    </li>

                                    <li class="list-group-item d-flex justify-content-between">

                                        Active Today

                                        <span class="badge bg-primary">

                                            {{ $activeUsers }}

                                        </span>

                                    </li>

                                    <li class="list-group-item d-flex justify-content-between">

                                        Weekly Active

                                        <span class="badge bg-info">

                                            {{ $weeklyActive }}

                                        </span>

                                    </li>

                                    <li class="list-group-item d-flex justify-content-between">

                                        Inactive (30 Days)

                                        <span class="badge bg-danger">

                                            {{ $monthlyInactive }}

                                        </span>

                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                </div> <!-- System Information -->

                <div class="card shadow mt-4">

                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">System Information</h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <tr>
                                <th width="30%">Laravel Version</th>
                                <td>{{ app()->version() }}</td>
                            </tr>

                            <tr>
                                <th>PHP Version</th>
                                <td>{{ PHP_VERSION }}</td>
                            </tr>

                            <tr>
                                <th>Server Time</th>
                                <td>{{ now()->format('d M Y h:i A') }}</td>
                            </tr>

                            <tr>
                                <th>Timezone</th>
                                <td>{{ config('app.timezone') }}</td>
                            </tr>

                        </table>

                    </div>

                </div>

                <!-- Latest Registered Users -->

                <div class="card shadow mt-4">

                    <div class="card-header bg-primary text-white">

                        <h5 class="mb-0">
                            Latest Registered Users
                        </h5>

                    </div>

                    <div class="card-body">

                        <table class="table table-hover table-bordered">

                            <thead class="table-light">

                                <tr>

                                    <th>ID</th>

                                    <th>Name</th>

                                    <th>Email</th>

                                    <th>Role</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($latestUsers as $user)

                                    <tr>

                                        <td>{{ $user->id }}</td>

                                        <td>{{ $user->name }}</td>

                                        <td>{{ $user->email }}</td>

                                        <td>

                                            <span class="badge bg-primary">

                                                {{ ucfirst($user->role) }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center">

                                            No Users Found

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- Progress -->

                <div class="card shadow mt-4">

                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">
                            User Progress
                        </h5>

                    </div>

                    <div class="card-body">

                        <h6>Active Users</h6>

                        <div class="progress mb-4">

                            <div class="progress-bar bg-success"
                                style="width: {{ $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0 }}%">

                                {{ $activeUsers }}

                            </div>

                        </div>

                        <h6>Admin Users</h6>

                        <div class="progress">

                            <div class="progress-bar bg-danger"
                                style="width: {{ $totalUsers > 0 ? ($admins / $totalUsers) * 100 : 0 }}%">

                                {{ $admins }}

                            </div>

                        </div>

                    </div>

                </div>

                <div class="mt-4">

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">

                        ← Back to Dashboard

                    </a>

                </div>

            </div>

        </div>

    </div>

    @push('scripts')

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                const ctx = document.getElementById('roleChart');

                new Chart(ctx, {

                    type: 'doughnut',

                    data: {

                        labels: ['Admins', 'Moderators', 'Users'],

                        datasets: [{

                            data: [

                            {{ $admins }},

                            {{ $moderators }},

                                {{ $users }}

                            ],

                            backgroundColor: [

                                '#dc3545',

                                '#ffc107',

                                '#0d6efd'

                            ]

                        }]

                    },

                    options: {

                        responsive: true,

                        plugins: {

                            legend: {

                                position: 'bottom'

                            }

                        }

                    }

                });

            });

        </script>

    @endpush

@endsection