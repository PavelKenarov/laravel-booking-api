<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class VerifySanctumToken extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (auth()->guard('sanctum')->check()) {
            return $next($request);
        }

        return response()->json(['error' => 'Invalid Token!'], 401);
    }
}
