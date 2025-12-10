<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4">Welcome to Middleware Demo Project</h1>
    <p class="lead">A demonstration of custom middleware in Laravel</p>
    
    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">🛡️ Role-Based Access</h5>
                    <p class="card-text">See how middleware restricts access based on user roles (Admin, Moderator, User).</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">📊 Activity Tracking</h5>
                    <p class="card-text">Middleware tracks user activity and updates last activity timestamp.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">⚡ Real-time Monitoring</h5>
                    <p class="card-text">Admin dashboard shows real-time user activity and status.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-5">
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-success btn-lg">Login to Continue</a>
            <div class="mt-3">
                <p class="text-muted">Use test accounts from login page</p>
            </div>
        @endauth
    </div>
    
    <div class="mt-5">
        <h4>Project Features:</h4>
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <ul class="list-group text-start">
                    <li class="list-group-item">✅ Custom middleware for activity tracking</li>
                    <li class="list-group-item">✅ Role-based access control middleware</li>
                    <li class="list-group-item">✅ Inactive user prevention middleware</li>
                    <li class="list-group-item">✅ Database session management</li>
                    <li class="list-group-item">✅ Real-time user status monitoring</li>
                    <li class="list-group-item">✅ Multiple user roles (Admin, Moderator, User)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection