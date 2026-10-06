<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MiddlewareLog;
use App\Models\UnauthorizedLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MiddlewareSandboxController extends Controller
{
    /**
     * Display Middleware Test Sandbox & Real-Time Security Radar
     */
    public function index(Request $request)
    {
        $users = User::all();
        $currentUser = Auth::user();

        // Audit Trail Logs
        $middlewareLogs = MiddlewareLog::with('user')->latest()->limit(15)->get();
        $unauthorizedLogs = UnauthorizedLog::with('user')->latest()->limit(15)->get();

        // Traffic Analytics Stats
        $stats = [
            'total_executions' => MiddlewareLog::count(),
            'unauthorized_attempts' => UnauthorizedLog::count(),
            'active_users' => User::where('last_activity_at', '>=', now()->subDays(30))->count(),
            'inactive_users' => User::where('last_activity_at', '<', now()->subDays(30))->count(),
            'unique_ips' => MiddlewareLog::distinct('ip_address')->count('ip_address') ?: 1,
        ];

        // Middleware Stack Definitions
        $availableMiddleware = [
            ['key' => 'role', 'name' => 'RoleMiddleware', 'desc' => 'Enforces strict role-based route permissions (admin, moderator, user)'],
            ['key' => 'check_role', 'name' => 'CheckUserRole', 'desc' => 'Validates authentication session & role authorization layer'],
            ['key' => 'inactive', 'name' => 'PreventInactiveUsers', 'desc' => 'Blocks users inactive for more than 30 days'],
            ['key' => 'activity', 'name' => 'TrackUserActivity', 'desc' => 'Logs timestamp & client metadata for every HTTP request'],
            ['key' => 'rate_limit', 'name' => 'RateLimiter', 'desc' => 'Throttles high-frequency request floods (Max 60 req/min)'],
            ['key' => 'ip_filter', 'name' => 'IpRestriction', 'desc' => 'Blacklists suspicious IP addresses (e.g. 10.0.0.1)'],
        ];

        return view('sandbox.index', compact(
            'users',
            'currentUser',
            'middlewareLogs',
            'unauthorizedLogs',
            'stats',
            'availableMiddleware'
        ));
    }

    /**
     * Live Middleware Pipeline Execution Simulator
     */
    public function simulate(Request $request)
    {
        $startTime = microtime(true);

        $userId = $request->input('user_id');
        $role = $request->input('role', 'guest');
        $targetRoute = $request->input('route', '/admin/dashboard');
        $clientIp = $request->input('ip_address', '127.0.0.1');
        $selectedMiddleware = $request->input('middleware', ['role', 'inactive', 'activity']);

        $user = $userId ? User::find($userId) : null;
        if ($user && $role !== 'guest') {
            $userRole = $user->role;
        } else {
            $userRole = $role;
        }

        $statusCode = 200;
        $statusBadge = 'ALLOWED';
        $message = 'Request successfully passed all middleware pipeline security layers.';
        $failedLayer = null;

        // 1. IP Blacklist Filter Check
        if (in_array($clientIp, ['10.0.0.1', '192.168.1.99', '45.33.22.11'])) {
            $statusCode = 403;
            $statusBadge = 'IP BLOCKED';
            $message = "Access Denied: Client IP {$clientIp} is blacklisted by IpRestriction middleware.";
            $failedLayer = 'IpRestriction';
        }

        // 2. Authentication Check
        elseif ($userRole === 'guest' && (str_contains($targetRoute, '/admin') || str_contains($targetRoute, '/moderator') || str_contains($targetRoute, '/dashboard'))) {
            $statusCode = 401;
            $statusBadge = 'UNAUTHORIZED';
            $message = 'Authentication Required: Please login to access protected route.';
            $failedLayer = 'AuthMiddleware';
        }

        // 3. Inactive User Check
        elseif ($user && ($userRole === 'inactive' || ($user->last_activity_at && $user->last_activity_at->lt(now()->subDays(30))))) {
            $statusCode = 403;
            $statusBadge = 'ACCOUNT SUSPENDED';
            $message = 'Access Denied: Your account has been inactive for over 30 days.';
            $failedLayer = 'PreventInactiveUsers';
        }

        // 4. Role Authorization Check
        elseif (str_contains($targetRoute, '/admin') && $userRole !== 'admin') {
            $statusCode = 403;
            $statusBadge = 'FORBIDDEN';
            $message = "Forbidden Access: Route requires 'admin' role, but your role is '{$userRole}'.";
            $failedLayer = 'RoleMiddleware';

            // Log Unauthorized Attempt
            UnauthorizedLog::create([
                'user_id' => $user?->id,
                'route' => $targetRoute,
                'required_role' => 'admin',
                'user_role' => $userRole,
                'ip_address' => $clientIp,
            ]);
        } elseif (str_contains($targetRoute, '/moderator') && !in_array($userRole, ['admin', 'moderator'])) {
            $statusCode = 403;
            $statusBadge = 'FORBIDDEN';
            $message = "Forbidden Access: Route requires 'moderator' or 'admin' role, but your role is '{$userRole}'.";
            $failedLayer = 'RoleMiddleware';

            UnauthorizedLog::create([
                'user_id' => $user?->id,
                'route' => $targetRoute,
                'required_role' => 'moderator,admin',
                'user_role' => $userRole,
                'ip_address' => $clientIp,
            ]);
        }

        $latencyMs = round((microtime(true) - $startTime) * 1000, 2);

        // Record Execution Log
        MiddlewareLog::create([
            'user_id' => $user?->id,
            'middleware_name' => $failedLayer ?: implode(', ', $selectedMiddleware),
            'route' => $targetRoute,
            'method' => 'GET',
            'ip_address' => $clientIp,
            'user_agent' => $request->userAgent() ?: 'Middleware Sandbox Simulator',
        ]);

        return response()->json([
            'success' => $statusCode === 200,
            'status_code' => $statusCode,
            'status_badge' => $statusBadge,
            'message' => $message,
            'user_name' => $user ? $user->name : 'Guest User',
            'role' => $userRole,
            'route' => $targetRoute,
            'client_ip' => $clientIp,
            'latency_ms' => $latencyMs,
            'failed_layer' => $failedLayer,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * 1-Click User Role Impersonator
     */
    public function impersonate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);

        return redirect()->route('sandbox.index')->with('success', "Impersonating {$user->name} (Role: {$user->role})!");
    }

    /**
     * Multi-Format Log Exporter (CSV, XLSX, JSON)
     */
    public function exportLogs(Request $request)
    {
        $format = strtolower($request->query('format', 'csv'));
        $filename = 'middleware_audit_logs_' . date('Y_m_d_His') . ".{$format}";

        $logs = MiddlewareLog::with('user')->get()->map(function ($log) {
            return [
                'ID' => $log->id,
                'User' => $log->user ? $log->user->name : 'Guest',
                'Middleware' => $log->middleware_name,
                'Route' => $log->route,
                'Method' => $log->method,
                'IP Address' => $log->ip_address,
                'Timestamp' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        });

        if ($format === 'json') {
            return response()->json($logs, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        // CSV or XLSX download streaming
        $headers = [
            'Content-Type' => $format === 'xlsx' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            if ($logs->isNotEmpty()) {
                fputcsv($file, array_keys($logs->first()));
                foreach ($logs as $row) {
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
