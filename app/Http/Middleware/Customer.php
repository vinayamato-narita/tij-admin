<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Customer
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
        if (!Auth::check()) {
            return redirect('/login' . '?url_redirect='. url()->full());
        }
        // if (!Auth::user()->change_pass_default_flag) {
        //     return redirect(route('administrator.change_pass_default.create'));
        // }
        return $next($request);
    }
}
