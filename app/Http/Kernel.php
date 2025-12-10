<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Global middleware (runs on every request)
    protected $middleware = [
        // ... other middleware
        \App\Http\Middleware\PreventInactiveUsers::class,
        \App\Http\Middleware\TrackUserActivity::class,
    ];
    
    // Route middleware groups
    protected $middlewareGroups = [
        'web' => [
            // ... other middleware
            \App\Http\Middleware\EncryptCookies::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],
        
        'api' => [
            // ... other middleware
        ],
    ];
    
    // Route middleware aliases
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\CheckUserRole::class,
        'activity' => \App\Http\Middleware\TrackUserActivity::class,
        'inactive' => \App\Http\Middleware\PreventInactiveUsers::class,
    ];
}