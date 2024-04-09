<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Web\SignUpRequest;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Log;
use App\Services\UserService;

class SignUp extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
        // code
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showSignup()
    {
        return view('web.auth.signup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SignUpRequest $request)
    {
        try {
            $datas = $request->except(['term']);
            $clientRole = CLIENT_ROLE;
            $this->userService->storeAccount($datas, $clientRole);
            notify()->success("Đăng ký tài khoản thành. Check email confirm.");
        } catch (Exception $exception) {
            Log::error('User signup error: ' . $e->getMessage());
            notify()->error('Có lỗi sảy ra');
            return redirect()->route('web.formSignup');
        }

        return redirect()->route('web.formLogin');
    }
}
