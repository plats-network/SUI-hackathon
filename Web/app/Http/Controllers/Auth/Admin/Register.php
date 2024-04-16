<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SignUpRequest;
use App\Mail\VerifyCodeEmail;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\RegisRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class Register extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SignUpRequest $request)
    {
        $confirmation_code = mt_rand(100000, 999999);
        try {
            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'confirmation_code' => $confirmation_code,
                'role' => CLIENT_ROLE,
                'email_verified_at' => now()
            ];
            User::create($data);
            Mail::to($data['email'])->send(new VerifyCodeEmail($confirmation_code));
        } catch (Exception $exception) {
            return redirect('cws/register')->withErrors(['message' => 'Error: Liên hệ admim']);
        }
        return redirect('auth/cws')->with(['success' => 'Đăng ký thành công vui lòng kiểm tra email để xác nhận']);
    }

    public function verify($code)
    {
        $user = User::where('confirmation_code',$code)->first();
        if ($user){
            $data = [
                'confirmation_code' => null
            ];
            User::where('id',$user->id)->update($data);
            return redirect('auth/cws')->with(['success' => 'Tài khoản xác nhận thành công']);
        } else {
            return redirect('register')->withErrors(['message' => 'Error: Liên hệ admim']);
        }
    }
}
