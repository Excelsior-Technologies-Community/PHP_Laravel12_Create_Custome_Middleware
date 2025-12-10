<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>User Dashboard</h3>
    </div>
    <div class="card-body">
        <h4>Welcome, {{ $user->name }}!</h4>
        <p>Email: {{ $user->email }}</p>
        <p>Role: <span class="badge bg-primary">{{ $user->role }}</span></p>
        <p>Last Activity: {{ $user->last_activity_at ? $user->last_activity_at->format('Y-m-d H:i:s') : 'Never' }}</p>
        
        <div class="mt-4">
            <h5>Quick Links:</h5>
            <a href="{{ route('profile') }}" class="btn btn-primary">My Profile</a>
            
            @if($user->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-success">Admin Dashboard</a>
            @endif
            
            @if(in_array($user->role, ['admin', 'moderator']))
                <a href="{{ route('management.dashboard') }}" class="btn btn-warning">Management Panel</a>
            @endif
        </div>
    </div>
</div>
@endsection