<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Log;
use Str;

class ConfirmController extends Controller
{
    public function __construct(
        private User $user,
    ) {
        //code
    }

    public function confirmRegis(Request $request, $token)
    {
        try {
            $flagExpried = true;
            $user = $this->checkConfirm($token);

            if ($user->confirm_at >= Carbon::now()) {
                $user->update([
                    'status' => USER_ACTIVE,
                    'confirm_at' => null,
                    'confirm_hash' => null,
                ]);

                notify()->success('Account confirm success');
                return redirect()->route('cws.formLogin');
            }
        } catch (\Exception $e) {
            Log::error('Error ' . $e->getMessage());
            notify()->error('Confirm account fail!');

            return redirect()->route('cws.formLogin');
        }

        return view('cws.a');
    }

    public function resendConfirm(Request $request, $token)
    {
        try {
            $user = $this->checkConfirm($token);

            $user->update([
                'confirm_hash' => Str::random(CONFIRM_HASH),
                'confirm_at' => Carbon::now()->addHours(CONFIRM_HOUR),
            ]);

            // Send mail

            notify()->success('Send mail confirm success');
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            notify()->error('Resend fail');

            return redirect()->route('cws.formLogin');
        }

        return redirect()->route('cws.formLogin');
    }

    private function checkConfirm($token)
    {
        $user = $this->user->whereConfirmHash($token)->first();

        if (!$user) {
            // 404
        }

        return $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
