<?php

namespace App\Http\Middleware;

use Closure;
use App\Garden;

class AuthResource
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
        if ($request->route('garden')) {
            $garden = Garden::find($request->route('garden'));
            if ($garden && $garden->user_id != auth()->user()->id) {
                return redirect('/garden');
            }
        }
        return $next($request);
    }
}
