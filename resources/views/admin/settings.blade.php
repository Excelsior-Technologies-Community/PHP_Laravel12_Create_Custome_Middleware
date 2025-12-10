<!-- resources/views/admin/settings.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3>Admin Settings</h3>
                <p class="mb-0">Administrator-only configuration panel</p>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle"></i> Warning</h5>
                    <p>This area is restricted to administrators only. Changes here affect the entire system.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6>Middleware Settings</h6>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="mb-3">
                                        <label class="form-label">Inactive User Threshold (Days)</label>
                                        <input type="number" class="form-control" value="30" min="1" max="365">
                                        <small class="text-muted">Users inactive for longer will be logged out</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" checked>
                                            Enable Activity Tracking
                                        </label>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" checked>
                                            Enable Role-Based Access Control
                                        </label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary" disabled>Save Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6>System Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Laravel Version</th>
                                        <td>{{ app()->version() }}</td>
                                    </tr>
                                    <tr>
                                        <th>PHP Version</th>
                                        <td>{{ phpversion() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Environment</th>
                                        <td>{{ app()->environment() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Maintenance Mode</th>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Session Driver</th>
                                        <td>{{ config('session.driver') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cache Driver</th>
                                        <td>{{ config('cache.default') }}</td>
                                    </tr>
                                </table>
                                
                                <div class="mt-3">
                                    <h6>Middleware Currently Active:</h6>
                                    <ul>
                                        <li><code>TrackUserActivity</code> - Logs user activity timestamps</li>
                                        <li><code>CheckUserRole</code> - Controls access based on roles</li>
                                        <li><code>PreventInactiveUsers</code> - Logs out inactive users</li>
                                        <li><code>Authenticate</code> - Standard Laravel auth middleware</li>
                                    </ul>
                                </div>
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
@endsection