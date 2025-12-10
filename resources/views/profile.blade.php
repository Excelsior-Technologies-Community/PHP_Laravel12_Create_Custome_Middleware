<!-- resources/views/profile.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>User Profile</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="mb-3">
                                <div style="width: 150px; height: 150px; background-color: #007bff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 48px; color: white;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                            <h4>{{ $user->name }}</h4>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'moderator' ? 'warning' : 'primary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Name:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Account Created:</th>
                                <td>{{ $user->created_at->format('F d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Last Activity:</th>
                                <td>
                                    @if($user->last_activity_at)
                                        {{ $user->last_activity_at->format('F d, Y h:i A') }}
                                        <br>
                                        <small class="text-muted">({{ $user->last_activity_at->diffForHumans() }})</small>
                                    @else
                                        Never
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 5)
                                        <span class="badge bg-success">Online</span>
                                    @else
                                        <span class="badge bg-secondary">Offline</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        
                        <div class="mt-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Activity Log</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Logged In</h6>
                            <small>Now</small>
                        </div>
                        <p class="mb-1">Successfully logged into the system</p>
                    </div>
                    
                    @if($user->last_activity_at)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Last Activity</h6>
                            <small>{{ $user->last_activity_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">Your last recorded activity</p>
                    </div>
                    @endif
                    
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Account Created</h6>
                            <small>{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">Your account was created</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection