<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Auth;
class VerifyCode
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && in_array(Auth::user()->role, [ADMIN_ROLE, CLIENT_ROLE]) && Auth::user()->confirmation_code == null) {
            return $next($request);
        } elseif (Auth::user() && !in_array(Auth::user()->role, [ADMIN_ROLE, CLIENT_ROLE]) && Auth::user()->confirmation_code == null) {
            return redirect('cws')->withErrors('message', 'You have not admin access');
        } else {
            return redirect('auth/cws')->withErrors('message', 'You have not admin access');
        }
    }
}
