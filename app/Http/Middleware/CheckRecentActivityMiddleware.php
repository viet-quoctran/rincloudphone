<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckRecentActivityMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = Cache::get('user-' . Auth::id() . '-last-activity');

            if ($lastActivity && now()->diffInMinutes($lastActivity) > 30) {
                Auth::logout();
                return response()->json(['error' => 'Bạn đã bị đăng xuất do không hoạt động'], 401);
            }

            Cache::put('user-' . Auth::id() . '-last-activity', now());
        }

        return $next($request);
    }
}
