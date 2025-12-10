<!-- resources/views/moderator/panel.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h3>Moderator Control Panel</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5>Welcome, Moderator!</h5>
                    <p>You have special moderator privileges to manage user content and activities.</p>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-users-cog fa-3x text-primary mb-3"></i>
                                <h5>User Management</h5>
                                <p>View and manage user accounts</p>
                                <button class="btn btn-outline-primary" disabled>Coming Soon</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-comments fa-3x text-success mb-3"></i>
                                <h5>Content Moderation</h5>
                                <p>Review and moderate user content</p>
                                <button class="btn btn-outline-success" disabled>Coming Soon</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                                <h5>Activity Reports</h5>
                                <p>View user activity reports</p>
                                <button class="btn btn-outline-info" disabled>Coming Soon</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Your Moderator Tools</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action" disabled>
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">View Reported Content</h6>
                                    <small class="text-muted">Feature in development</small>
                                </div>
                                <p class="mb-1">Review content that has been reported by users</p>
                            </a>
                            
                            <a href="#" class="list-group-item list-group-item-action" disabled>
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">User Warnings</h6>
                                    <small class="text-muted">Feature in development</small>
                                </div>
                                <p class="mb-1">Issue warnings to users for guideline violations</p>
                            </a>
                            
                            <a href="#" class="list-group-item list-group-item-action" disabled>
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Moderation Log</h6>
                                    <small class="text-muted">Feature in development</small>
                                </div>
                                <p class="mb-1">View history of your moderation actions</p>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to User Dashboard</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-success">Go to Admin Dashboard</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection