<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Log;

class ForgotPassword extends Controller
{
    public function __construct(
        private User $user,
    ) {
        // code
    }

    public function showForgetPasswordForm()
    {
        return view('web.auth.forgetPassword');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        try {
            $user = $this->user
                ->whereEmail($request->email)
                ->first();

            if (!$user) {
                notify()->error('Email không tồn tại');
                return redirect()->route('web.formForgot');
            }

            notify()->success('Chúng tôi đã gửi link reset mật khẩu đến email của bạn');
            // Send Mail

        } catch (\Exception $e) {
            Log::error('User reset password error: ' . $e->getMessage());
            notify()->error('Error reset password');

            return redirect()->route('web.formForgot');
        }

        return redirect()->route('web.formLogin');


        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        ResetPassword::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->email)->send(new ForgetPassword($token));

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm($token)
    {
        return view('web.auth.forgetPasswordLink', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => \Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/client/login')->with('message', 'Your password has been changed!');
    }
}
