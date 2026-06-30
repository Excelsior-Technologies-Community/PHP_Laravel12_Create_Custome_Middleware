<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MiddlewareLog;

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


            // Save middleware activity in database
            MiddlewareLog::create([

                'user_id' => Auth::id(),

                'middleware_name' => 'TrackUserActivity',

                'route' => $request->path(),

                'method' => $request->method(),

                'ip_address' => $request->ip(),

                'user_agent' => $request->userAgent(),

            ]);



            // Existing Laravel log (keep this)
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