<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;

class ThrottleLoginMiddleware
{
    public function handle($request, Closure $next)
    {
        $key = 'login-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json(['error' => 'Quá nhiều lần thử, vui lòng thử lại sau 1 phút'], 429);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}