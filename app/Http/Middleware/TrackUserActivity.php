<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Track activity only for authenticated users
        if (Auth::check()) {
            Auth::user()->update([
                'last_activity_at' => now()
            ]);
            
            // Also log the activity (optional)
            \Log::info('User activity', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'time' => now()
            ]);
        }
        
        return $response;
    }
}