<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Auth\Authenticates;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    use Authenticates;

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showFormLogin()
    {
        if (Auth::guard('web')->user()) {
            return redirect('/');
        }

        return view('admin.auth.login');
    }

    /**
     * Redirect URl after the user was authenticated.
     *
     * @return string
     */
    public function redirectTo()
    {
        return route('cws.home');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }
}
