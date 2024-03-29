<?php

namespace App\Http\Middleware;

use Closure;

class Logged
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
        if (!$request->session()->has('users')&&($request->wantsJson()||$request->ajax())) {
            return response('Unauthorized.', 401);
        }elseif (!$request->session()->has('users')) {
            return redirect('/login')->with('redirect', $request->path());
        }
        return $next($request);
    }
}
