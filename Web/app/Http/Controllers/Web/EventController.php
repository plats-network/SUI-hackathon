<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailLoginEvent;
use App\Models\NFT\NFTMint;
use App\Models\NFT\UserNft;
use Illuminate\Support\Facades\Mail;
use Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{

    public function register($id)
    {
        // get nft claim
//        $nft = NFTMint::where([
//            'status' => NFTMint::ACTIVE,
//            'task_id' => $id
//        ])->first();
//
//        // check user nft
//        $check = UserNft::where([
//            'user_id' => auth()->user()->id,
//            'task_id' => $id
//        ])->first();
//
//        if ($check) {
//            return about(404);
//        }
//
//        // fake to demo
//        $userNft = new UserNft();
//        $userNft->user_id = auth()->user()->id;
//        $userNft->nft_mint_id = $nft->id;
//        $userNft->type = $nft->type;
//        $userNft->task_id = $id;
//        $userNft->save();
//
//        if ($nft) {
//            // send email
//            $qrCode = base64_encode(QrCode::format('png')->size(250)->generate(route('nft.claim', $nft->id)));
//            $nft->status = NFTMint::SENDING;
//            $nft->save();
//
//            // create user mint
//            // save nft
//            $userNft = new UserNft();
//            $userNft->user_id = auth()->user()->id;
//            $userNft->nft_mint_id = $nft->id;
//            $userNft->type = $nft->type;
//            $userNft->task_id = $id;
//            $userNft->save();

        $nft = NFTMint::where([
            'task_id' => $id,
            'status' => 1
        ])->first();

        if ($nft) {
            dd($nft);
        }

        // fake to demo
        $userNft = new UserNft();
        $userNft->user_id = auth()->user()->id;
        $userNft->nft_mint_id = $nft ? $nft->id + 1 : 1;
        $userNft->type = $nft->type;
        $userNft->task_id = $id;
        $userNft->save();

//            Mail::to(auth()->user()->email)->send(new SendEmailLoginEvent($qrCode));
//        }

        return redirect(route('web.events.show', ['id'=>$id]));
    }
}
