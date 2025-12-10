<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Admin Dashboard</h3>
    </div>
    <div class="card-body">
        <h4>User Activity Monitor</h4>
        
        <table class="table table-striped">
            <thead>
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
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">{{ $user->role }}</span></td>
                    <td>{{ $user->last_activity_at ? $user->last_activity_at->diffForHumans() : 'Never' }}</td>
                    <td>
                        @if($user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 5)
                            <span class="badge bg-success">Online</span>
                        @elseif($user->last_activity_at && $user->last_activity_at->diffInHours(now()) < 24)
                            <span class="badge bg-warning">Recently Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-4">
            <a href="{{ route('admin.statistics') }}" class="btn btn-info">View Statistics</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to User Dashboard</a>
        </div>
    </div>
</div>
@endsection