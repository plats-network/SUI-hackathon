<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    /*
        * connectWallet
        * Sau khi connect xong thì frontend gửi lên backend 2 thông tin:
        * wallet_address và wallet_name (account name: optional)
   Backend xử lý nếu chưa có trong DB thì đăng ký user mới.
       Nếu có rồi thì thôi. Cuối cùng xử lý để sao cho user đó đã ở trạng thái đã login.
        * */
    public function connectWallet(Request $request)
    {
        $body_class = '';
        //wallet_address
        $wallet_address = $request->wallet_address;
        //wallet_name
        $wallet_name = $request->wallet_name;
        //Check user by wallet_address and wallet_name in db
        //If not exist then create new user
        //If exist then login
        //If login success then redirect to home page
        //If login fail then redirect to login page
        $user = User::where('wallet_address', $wallet_address)
            //->where('wallet_name', $wallet_name)
            ->first();

        if ($user) {
            //login
            //Auth::guard('quest')->login($user);
            //return redirect()->route('quest.home');
        } else {
            //create new user
            $user = new User();
            $user->wallet_address = $wallet_address;
            $user->wallet_name = $wallet_name;

            $password = bcrypt($wallet_address . $wallet_name);

            $user->name = $wallet_name;
            $user->email = $wallet_address . $wallet_name . '@gmail.com';
            $user->role = ADMIN_ROLE;
            $user->phone = '';
            $user->address = '';
            $user->password = $password;

            $user->save();
            //login
            //Auth::guard('quest')->login($user);
        }


        //Json output
        $output = [
            'status' => 'success',
            'message' => 'Connect wallet success',
            'data' => [
                'user' => $user
            ]
        ];

        return response()->json($output);

        //return view('frontend.connect-wallet', compact('body_class'));
    }


    //walletLogin
    public function walletLogin(Request $request)
    {
        $body_class = '';
        $wallet_address = $request->wallet_address;
        //Check $wallet_address
        if (!$wallet_address) {
            //Json response
            $output = [
                'status' => 'fail',
                'message' => 'Wallet address is required',
                'data' => [
                    'user' => null
                ]
            ];

            return response()->json($output);
        }
        //wallet_name
        $wallet_name = $request->wallet_name;
        //Check user by wallet_address and wallet_name in db
        //If not exist then create new user
        //If exist then login
        //If login success then redirect to home page
        //If login fail then redirect to login page
        $user = User::where('wallet_address', $wallet_address)
            //->where('wallet_name', $wallet_name)
            ->first();

        //Check !user
        if ($user == false) {
            //Json response
            $output = [
                'status' => 'fail',
                'message' => 'Login fail',
                'data' => [
                    'user' => null
                ]
            ];

            return response()->json($output);
        }

        $msg = 'Login fail';
        $status = 'fail';
        if ($user) {
            $user->role = ADMIN_ROLE;
            $user->save();
            //login
            Auth::login($user);

            $msg = 'Login success';
            $status = 'success';

            return redirect()->route('web.home');
        }
        //Json response
        $output = [
            'status' => $status,
            'message' => $msg,
            'data' => [
                'user' => $user
            ]
        ];

        return redirect()->route('web.home');
    }
}
