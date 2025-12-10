<!-- resources/views/management/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Management Dashboard</h3>
                <p class="mb-0">Accessible to Admins and Moderators</p>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h5>Welcome to Management Panel!</h5>
                    <p>You have access to this area because your role is: 
                        <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : 'warning' }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </p>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Quick Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center p-3 border rounded bg-light">
                                            <h2>{{ App\Models\User::count() }}</h2>
                                            <small>Total Users</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3 border rounded bg-light">
                                            <h2>{{ App\Models\User::where('last_activity_at', '>=', now()->subDay())->count() }}</h2>
                                            <small>Active Today</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Your Access Level</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Admin Dashboard
                                        @if(auth()->user()->role === 'admin')
                                            <span class="badge bg-success rounded-pill">Access Granted</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">Access Denied</span>
                                        @endif
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Moderator Panel
                                        <span class="badge bg-success rounded-pill">Access Granted</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Management Dashboard
                                        <span class="badge bg-success rounded-pill">Access Granted</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        User Dashboard
                                        <span class="badge bg-success rounded-pill">Access Granted</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Available Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(auth()->user()->role === 'admin')
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cogs fa-2x text-danger mb-2"></i>
                                        <h6>System Configuration</h6>
                                        <p>Admin-only system settings</p>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-danger">Go to Admin Panel</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-tasks fa-2x text-warning mb-2"></i>
                                        <h6>Content Moderation</h6>
                                        <p>Moderate user content</p>
                                        <a href="{{ route('moderator.panel') }}" class="btn btn-sm btn-outline-warning">Go to Moderator Panel</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                                        <h6>View Reports</h6>
                                        <p>View system statistics</p>
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('admin.statistics') }}" class="btn btn-sm btn-outline-info">View Statistics</a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>Admin Only</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to User Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection