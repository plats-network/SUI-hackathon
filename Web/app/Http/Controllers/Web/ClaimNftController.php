<?php

namespace App\Http\Controllers\Web;

use App\Jobs\SendTicket;
use App\Mail\NFTNotification;
use App\Mail\OrderCreated;
use App\Mail\SendEmailLoginEvent;
use App\Mail\SendNFTMail;
use App\Mail\SendTicket as EmailSendTicket;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Task as Event;
use MagicAdmin\Resource\Token;
use App\Models\{NFT\NFT, NFT\NFTMint, NFT\UserNft, Task, User, TravelGame, Sponsor};
use Illuminate\Support\Str;
use App\Models\Event\{EventUserTicket, TaskEvent, TaskEventDetail, UserCode, UserEventLike, UserJoinEvent};
use App\Services\Admin\{
    EventService,
    TaskService
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Magic;
use MagicAdmin\Exception\DIDTokenException;

class ClaimNftController extends Controller
{
    public function claim($id)
    {
        $nft = NFTMint::find($id);
        $userMint = UserNft::where([
            'user_id' => \auth()->user()->id,
            'nft_mint_id' => $nft->id
        ])->first();

        if ($userMint) {
            $nft->status = NFTMint::ACCEPTED;
            $nft->save();
            return view('web.nft.claim')->with(['nft' => $nft]);
        }

        return abort(404);
    }

    public function claimAction($id)
    {
        $nft = NFTMint::find($id);
        $userMint = UserNft::where([
            'user_id' => \auth()->user()->id,
            'nft_mint_id' => $nft->id
        ])->first();

        if ($userMint) {
            $nft->status = NFTMint::ACCEPTED;
            $nft->save();
            return view('web.nft.claim')->with(['nft' => $nft]);
        }

        return abort(404);
    }
}
