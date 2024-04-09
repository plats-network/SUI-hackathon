<?php

namespace App\Http\Controllers;

use App\Models\NFT\NFT;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function storePaymentRequest(Request $request){
        $input = $request->all();

        return response()->json([
            'status' => true,
            'message' => 'Payment Request Created Successfully',
            'data' => $input
        ]);
    }
    public function paymentLink(Request $request)
    {
        $input = $request->all();
        //event_id
        $event_id = $input['event_id']??null;
        //Get NFT ID Record
        $nft_id = $input['nft_id']??null;
        $nft = NFT::where('id', $nft_id)->first();
        //Check NFT Record
        if(!$nft) {
            return response()->json([
                'status' => false,
                'message' => 'NFT Record Not Found'
            ]);
        }

        //http://localhost:5173/payment-nft?nft_name=tranchinh&&nft_des=xinchao&&nft_size=5&&blockchain=alphe&&img=urlimage
        //Create Payment Link for NFT
        $dataNFT = [
            'event_id' => $nft->task_id,
            'user_id' => $nft->owner_id,
            'blockchain' => $nft->blockchain??'aleph',
            'nft_id' => $nft->id,
            'nft_name' => $nft->name,
            'nft_des' => $nft->description,
            'img' => $nft->image_url,
            'nft_size' => $nft->size,

            //'nft_asset_contract' => $nft->asset_contract,
            'email' => $input['email']??null,
            'amount' => $input['amount']??null,
            'description' => $input['description']??null,
            'due_date' => $input['due_date']??null,
            'invoice_number' => $input['invoice_number']??null,
            'paid' => false,
            'link' => $input['link']??null,
            'stripe_payment_id' => $input['stripe_payment_id']??null,
            'network' => $input['network']??null,
        ];

        //Callback URL
        $callback_url = route('payment-success', ['nft_id' => $dataNFT['nft_id']]);

        $dataNFT['callback_url'] = $callback_url;
        //MD5 Hash
        $dataNFT['hash'] = md5(json_encode($dataNFT));

        //Create Payment Link with data. Set for router payment-request
        $paymentLink = route('payment-request', $dataNFT);
        //Url Payment
        //$urlPayment = 'http://localhost:5173';
        $urlPayment = 'https://platsevent.web.app';
        //Replace $paymentLink with $urlPayment
        $paymentLink = str_replace(url('/'), $urlPayment, $paymentLink);

        //Return Payment Link
        return redirect($paymentLink);
    }

    //paymentSuccess
    public function paymentSuccess(Request $request)
    {
        $input = $request->all();
        //Get NFT ID Record
        $nft_id = $input['nft_id']??null;
        $nftModel = NFT::query()->where('id', $nft_id)->first();

        //Check NFT Record
        if(!$nftModel) {
            return response()->json([
                'status' => false,
                'message' => 'Payment Detail Record Not Found'
            ]);
        }

        //Update Payment Detail
        $nftModel->update([
            'paid' => true,
            'stripe_payment_id' => $input['stripe_payment_id']??null,
        ]);

        //Return Payment Link
        return response()->json([
            'status' => true,
            'message' => 'Payment Detail Updated Successfully',
            'data' => $nftModel
        ]);
    }
}
