<?php

namespace App\Http\Middleware;

use Closure;

class Adminstrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }
        abort(403, "You don't have a permission to lock this thread");
    }
}
