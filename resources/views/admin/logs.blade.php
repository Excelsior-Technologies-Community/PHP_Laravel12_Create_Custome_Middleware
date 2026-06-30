@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Security Monitoring Dashboard</h2>
            <p class="text-muted mb-0">Middleware activity and unauthorized access tracking</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
            Back Dashboard
        </a>
    </div>


    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h6>Total Middleware Requests</h6>
                    <h2>{{ $middlewareLogs->total() }}</h2>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body">
                    <h6>Unauthorized Attempts</h6>
                    <h2>{{ $unauthorizedLogs->total() }}</h2>
                </div>
            </div>
        </div>

    </div>



    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h5 class="mb-0">Middleware Activity Logs</h5>
            <span class="badge bg-success">Live Tracking</span>
        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Middleware</th>
                            <th>Route</th>
                            <th>Method</th>
                            <th>IP Address</th>
                            <th>Date</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($middlewareLogs as $log)

                        <tr>

                            <td class="fw-semibold">
                                {{ $log->user->name ?? 'Guest' }}
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $log->middleware_name }}
                                </span>
                            </td>

                            <td>
                                <code>{{ $log->route }}</code>
                            </td>

                            <td>
                                <span class="badge bg-dark">
                                    {{ $log->method }}
                                </span>
                            </td>

                            <td>
                                {{ $log->ip_address }}
                            </td>

                            <td>
                                {{ $log->created_at->diffForHumans() }}
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="text-center py-4">
                                No activity found
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">
            {{ $middlewareLogs->links('pagination::bootstrap-5') }}
        </div>

    </div>


    

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Unauthorized Access History</h5>
        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>User</th>
                            <th>Route</th>
                            <th>User Role</th>
                            <th>Required Role</th>
                            <th>Date</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($unauthorizedLogs as $log)

                        <tr>

                            <td>
                                {{ $log->user->name ?? 'Guest' }}
                            </td>

                            <td>
                                <code>{{ $log->route }}</code>
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $log->user_role }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-danger">
                                    {{ $log->required_role }}
                                </span>
                            </td>

                            <td>
                                {{ $log->created_at->diffForHumans() }}
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center py-4">
                                No unauthorized attempts
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">
            {{ $unauthorizedLogs->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>

@endsection