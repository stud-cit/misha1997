<?php

namespace App\Http\Middleware;

use Closure;

class AuthUser
{
    public function handle($request, Closure $next)
    {
        $request->session()->forget('authenticated');
        $request->session()->forget('user');
        return $next($request);
    }
}
