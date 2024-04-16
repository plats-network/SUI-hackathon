<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisAdmin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Hash};

class RegisterAdmin extends Controller
{
    const ROLE_CLIENT = 3;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisAdmin $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => self::ROLE_CLIENT,
            'email_verified_at' => Carbon::now('Asia/Ho_Chi_Minh')
        ]);

        return response()->json([
            'message' => 'Successful!',
            'user' => $user
        ], 200);
    }
}
