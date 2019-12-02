<?php

namespace App\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Cookie;

class RefreshAuthCookie {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $authCookie = Auth::getRecallerName();
        $endDate = Carbon::parse($request->cookie("{$authCookie}_expiry"));

        if ($endDate < Carbon::now()->addMinutes(config('auth.duration') * 2/ 3)) {
            Cookie::queue($authCookie, $request->cookie($authCookie), config('auth.duration') );
            Cookie::queue("{$authCookie}_expiry",  Carbon::now()->addMinutes(config('auth.duration') ), config('auth.duration') );
        }
        return $next($request);
    }
}
