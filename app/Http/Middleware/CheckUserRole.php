<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        
        // Check if user has any of the required roles
        if (!in_array($user->role, $roles)) {
            // You can customize the response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Required role(s): ' . implode(', ', $roles),
                    'your_role' => $user->role
                ], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }
        
        return $next($request);
    }
}