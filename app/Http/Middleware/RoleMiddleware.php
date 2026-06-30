<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UnauthorizedLog;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }


        if (!in_array(Auth::user()->role, $roles)) {


            // Store unauthorized access attempt
            UnauthorizedLog::create([

                'user_id' => Auth::id(),

                'route' => $request->path(),

                'required_role' => implode(',', $roles),

                'user_role' => Auth::user()->role,

                'ip_address' => $request->ip(),

            ]);


            abort(403, 'Unauthorized.');
        }


        return $next($request);
    }
}