<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RestaurantAdminAndCashier
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
        if(Auth::user()->privilege=="restaurant_cashier"||Auth::user()->privilege=="restaurant_admin"){
            return $next($request);
        }
        return abort(403);
    }
}
