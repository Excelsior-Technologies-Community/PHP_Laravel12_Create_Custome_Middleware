<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class PreventInactiveUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $inactiveDays = 30; // Configurable
            
            // Check if user has been inactive for too long
            if ($user->last_activity_at && 
                $user->last_activity_at->diffInDays(Carbon::now()) > $inactiveDays) {
                
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Your account has been inactive for too long. Please login again.');
            }
        }
        
        return $next($request);
    }
}