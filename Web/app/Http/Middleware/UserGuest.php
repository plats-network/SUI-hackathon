<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class UserGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       

        if (Auth::user() && in_array(Auth::user()->role, [USER_ROLE, GUEST_ROLE, ADMIN_ROLE,CLIENT_ROLE])) {

            return $next($request);
        } 
        
        if($request->segment(1) == 'quiz-game') {

            session()->put('type', 'quiz-game');
        }

        return redirect()->route('web.login');

    }
}
