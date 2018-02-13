<?php

namespace App\Http\Middleware;

use Closure;

class PickingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$limited_time = ($request->user()->limited_time) ?: 'now';
			
		if (strtotime($limited_time) <= time()) {
            return redirect('picking/nottime');
        }

        return $next($request);
    }
}
