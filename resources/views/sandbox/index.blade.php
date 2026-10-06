@extends('layouts.app')

@section('title', 'Middleware Test Sandbox & Security Radar')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Banner -->
    <div class="bg-white p-4 rounded-4 shadow-sm border mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h3 class="fw-bold mb-1 text-dark">
                <i class="fa-solid fa-shield-halved text-primary me-2"></i>Middleware Test Sandbox & Live Security Simulator
            </h3>
            <p class="text-muted mb-0 small">Simulate custom middleware pipelines, impersonate user roles & inspect security audit logs</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-lock me-1"></i> Admin Panel
            </a>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-download me-1"></i> Export Audit Logs
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="{{ route('sandbox.export', ['format' => 'csv']) }}"><i class="fa-solid fa-file-csv text-success me-2"></i> CSV Audit Log</a></li>
                    <li><a class="dropdown-item" href="{{ route('sandbox.export', ['format' => 'xlsx']) }}"><i class="fa-solid fa-file-excel text-primary me-2"></i> XLSX Spreadsheet</a></li>
                    <li><a class="dropdown-item" href="{{ route('sandbox.export', ['format' => 'json']) }}"><i class="fa-solid fa-file-code text-warning me-2"></i> JSON Payload</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Traffic & Security KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary h-100">
                <div class="text-muted small fw-semibold">TOTAL REQUESTS LOGGED</div>
                <div class="h2 fw-bold text-primary mb-0 mt-1">{{ number_format($stats['total_executions']) }}</div>
                <small class="text-muted">TrackUserActivity Pipeline</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger h-100">
                <div class="text-muted small fw-semibold">UNAUTHORIZED ATTEMPTS</div>
                <div class="h2 fw-bold text-danger mb-0 mt-1">{{ number_format($stats['unauthorized_attempts']) }}</div>
                <small class="text-muted">403 Forbidden Responses</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success h-100">
                <div class="text-muted small fw-semibold">ACTIVE USERS (<=30 DAYS)</div>
                <div class="h2 fw-bold text-success mb-0 mt-1">{{ $stats['active_users'] }}</div>
                <small class="text-muted">Allowed Through Pipeline</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning h-100">
                <div class="text-muted small fw-semibold">INACTIVE USERS (>30 DAYS)</div>
                <div class="h2 fw-bold text-warning mb-0 mt-1">{{ $stats['inactive_users'] }}</div>
                <small class="text-muted">PreventInactiveUsers Blocked</small>
            </div>
        </div>
    </div>

    <!-- 1-Click Role Impersonator Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-1"><i class="fa-solid fa-user-secret text-warning me-2"></i>1-Click User Role Impersonator</h5>
            <p class="text-muted small mb-0">Switch active session role instantly to test route permission middleware</p>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @foreach($users as $u)
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 bg-light d-flex flex-column justify-between h-100 {{ Auth::id() == $u->id ? 'border-primary border-2 shadow-sm' : '' }}">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark">{{ $u->name }}</span>
                                    @if($u->role === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($u->role === 'moderator')
                                        <span class="badge bg-primary">Moderator</span>
                                    @elseif($u->email === 'inactive@example.com')
                                        <span class="badge bg-warning text-dark">Inactive</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </div>
                                <small class="text-muted d-block">{{ $u->email }}</small>
                            </div>
                            <div class="mt-3">
                                @if(Auth::id() == $u->id)
                                    <button disabled class="btn btn-success btn-sm w-100 fw-bold"><i class="fa-solid fa-user-check me-1"></i> Active Session</button>
                                @else
                                    <form action="{{ route('sandbox.impersonate', $u->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 fw-bold">Impersonate {{ ucfirst($u->role) }}</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Live Middleware Pipeline Simulator Form -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-vial text-primary me-2"></i>Live Middleware Execution Simulator</h5>
                    <p class="text-muted small mb-0">Configure request parameters and execute middleware pipeline evaluation</p>
                </div>
                <div class="card-body p-4">
                    <form id="simulatorForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Target Route</label>
                                <select name="route" class="form-select">
                                    <option value="/admin/dashboard" selected>/admin/dashboard (Admin Only)</option>
                                    <option value="/admin/settings">/admin/settings (Admin Only)</option>
                                    <option value="/moderator/panel">/moderator/panel (Moderator & Admin)</option>
                                    <option value="/management">/management (Admin & Moderator)</option>
                                    <option value="/dashboard">/dashboard (Authenticated Users)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">User Role Context</label>
                                <select name="role" class="form-select">
                                    <option value="admin" {{ (Auth::user()?->role === 'admin') ? 'selected' : '' }}>👑 Admin</option>
                                    <option value="moderator" {{ (Auth::user()?->role === 'moderator') ? 'selected' : '' }}>🛡️ Moderator</option>
                                    <option value="user" {{ (Auth::user()?->role === 'user' && Auth::user()?->email !== 'inactive@example.com') ? 'selected' : '' }}>👤 Active User</option>
                                    <option value="inactive" {{ (Auth::user()?->email === 'inactive@example.com') ? 'selected' : '' }}>⚠️ Inactive User (>30 days)</option>
                                    <option value="guest">🚪 Guest (Unauthenticated)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Client IP Address</label>
                                <select name="ip_address" class="form-select">
                                    <option value="127.0.0.1" selected>127.0.0.1 (Localhost Whitelisted)</option>
                                    <option value="192.168.1.100">192.168.1.100 (Internal Network)</option>
                                    <option value="10.0.0.1">10.0.0.1 (Blacklisted Suspicious IP)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Active User Object</label>
                                <input type="text" class="form-control bg-light" readonly value="{{ Auth::check() ? Auth::user()->name . ' (' . Auth::user()->email . ')' : 'Guest Session' }}">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold d-block">Active Middleware Stack Layers</label>
                            <div class="row g-2">
                                @foreach($availableMiddleware as $mw)
                                    <div class="col-md-6">
                                        <div class="form-check p-2 border rounded-3 bg-light">
                                            <input class="form-check-input ms-1" type="checkbox" name="middleware[]" value="{{ $mw['key'] }}" id="mw_{{ $mw['key'] }}" checked>
                                            <label class="form-check-label ms-2 small fw-bold text-dark" for="mw_{{ $mw['key'] }}">
                                                {{ $mw['name'] }}
                                            </label>
                                            <div class="extra-small text-muted ms-4">{{ $mw['desc'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4 text-end border-top pt-3">
                            <button type="button" id="runSimulationBtn" class="btn btn-primary btn-lg fw-bold px-4 rounded-3 shadow-sm">
                                <i class="fa-solid fa-play me-2"></i>Run Pipeline Simulation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Simulation Result Output Box -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white">
                <div class="card-header bg-transparent border-secondary pt-3 px-3 pb-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold small text-light"><i class="fa-solid fa-terminal text-success me-2"></i>Pipeline Evaluation Output</span>
                    <span id="resultBadge" class="badge bg-secondary">READY</span>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-between">
                    <div>
                        <div class="p-3 bg-black rounded-3 font-monospace mb-3" style="border: 1px solid #333;">
                            <div class="d-flex justify-content-between text-muted small mb-2 border-b pb-1">
                                <span>STATUS CODE: <strong id="resultStatusCode" class="text-white">---</strong></span>
                                <span>LATENCY: <strong id="resultLatency" class="text-warning">--- ms</strong></span>
                            </div>
                            <div id="resultMessage" class="text-success small mb-2">Select parameters and click "Run Pipeline Simulation" to test middleware stack.</div>
                            <div class="text-muted extra-small">USER: <span id="resultUser" class="text-light">N/A</span> | ROLE: <span id="resultRole" class="text-light">N/A</span></div>
                        </div>

                        <div id="resultDetailsBox" class="p-3 rounded-3 bg-secondary bg-opacity-25 border border-secondary small d-none">
                            <h6 class="fw-bold text-warning mb-2"><i class="fa-solid fa-shield-cat me-1"></i>Security Diagnostics</h6>
                            <ul class="mb-0 ps-3 text-light">
                                <li>Target Route: <code id="diagRoute" class="text-info">---</code></li>
                                <li>Client IP: <code id="diagIp" class="text-info">---</code></li>
                                <li>Evaluated Middleware: <span id="diagFailedLayer" class="text-danger fw-bold">All Passed</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-muted extra-small mt-3 text-center">
                        <i class="fa-solid fa-bolt me-1"></i> Sub-millisecond execution latency benchmarks
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Trail Logs Table -->
    <div class="row g-4">
        <!-- Executed Middleware Logs -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-list-check text-primary me-2"></i>Executed Request Logs</h5>
                    <p class="text-muted small mb-0">TrackUserActivity middleware log records</p>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle extra-small">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Middleware</th>
                                    <th>Route</th>
                                    <th>IP</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($middlewareLogs as $log)
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $log->user ? $log->user->name : 'Guest' }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $log->middleware_name }}</span></td>
                                        <td><code>{{ $log->route }}</code></td>
                                        <td class="font-monospace text-muted">{{ $log->ip_address }}</td>
                                        <td class="text-muted">{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">No middleware logs recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unauthorized Attempts Logs -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Security Violation Radar</h5>
                    <p class="text-muted small mb-0">403 Forbidden / Unauthorized permission violations</p>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle extra-small">
                            <thead class="table-light">
                                <tr>
                                    <th>User Context</th>
                                    <th>Route</th>
                                    <th>Role / Required</th>
                                    <th>IP</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unauthorizedLogs as $uLog)
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $uLog->user ? $uLog->user->name : 'Guest' }}</td>
                                        <td><code class="text-danger">{{ $uLog->route }}</code></td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $uLog->user_role ?: 'guest' }}</span>
                                            <i class="fa-solid fa-arrow-right mx-1 text-muted"></i>
                                            <span class="badge bg-danger">{{ $uLog->required_role }}</span>
                                        </td>
                                        <td class="font-monospace text-muted">{{ $uLog->ip_address }}</td>
                                        <td class="text-muted">{{ $uLog->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">No unauthorized attempts recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const runBtn = document.getElementById('runSimulationBtn');
    const form = document.getElementById('simulatorForm');
    const resultBadge = document.getElementById('resultBadge');
    const resultStatusCode = document.getElementById('resultStatusCode');
    const resultLatency = document.getElementById('resultLatency');
    const resultMessage = document.getElementById('resultMessage');
    const resultUser = document.getElementById('resultUser');
    const resultRole = document.getElementById('resultRole');
    const resultDetailsBox = document.getElementById('resultDetailsBox');
    const diagRoute = document.getElementById('diagRoute');
    const diagIp = document.getElementById('diagIp');
    const diagFailedLayer = document.getElementById('diagFailedLayer');

    runBtn.addEventListener('click', function() {
        runBtn.disabled = true;
        runBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Simulating...`;

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        fetch('{{ route("sandbox.simulate") }}?' + params.toString())
            .then(res => res.json())
            .then(data => {
                runBtn.disabled = false;
                runBtn.innerHTML = `<i class="fa-solid fa-play me-2"></i>Run Pipeline Simulation`;

                resultBadge.textContent = data.status_badge;
                resultBadge.className = 'badge ' + (data.success ? 'bg-success' : 'bg-danger');
                resultStatusCode.textContent = data.status_code;
                resultLatency.textContent = data.latency_ms + ' ms';
                resultMessage.textContent = data.message;
                resultMessage.className = data.success ? 'text-success small mb-2' : 'text-danger small mb-2';
                resultUser.textContent = data.user_name;
                resultRole.textContent = data.role;

                diagRoute.textContent = data.route;
                diagIp.textContent = data.client_ip;
                diagFailedLayer.textContent = data.failed_layer ? 'Failed Layer: ' + data.failed_layer : 'Passed All Middleware Layers';
                diagFailedLayer.className = data.failed_layer ? 'text-danger fw-bold' : 'text-success fw-bold';
                resultDetailsBox.classList.remove('d-none');
            })
            .catch(err => {
                runBtn.disabled = false;
                runBtn.innerHTML = `<i class="fa-solid fa-play me-2"></i>Run Pipeline Simulation`;
                alert('Simulation error.');
            });
    });
});
</script>
@endpush
