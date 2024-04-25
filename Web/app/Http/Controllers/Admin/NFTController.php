<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NFT;
use App\Models\NFT\UserNft;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NFTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $nfts = NFT::latest()->paginate(5);

        return view('nfts.index',compact('nfts'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('nfts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        NFT::create($request->all());

        return redirect()->route('nfts.index')
            ->with('success','NFT created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NFT $product): View
    {
        return view('nfts.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NFT $product): View
    {
        return view('nfts.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NFT $product): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('nfts.index')
            ->with('success','NFT updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NFT $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('nfts.index')
            ->with('success','NFT deleted successfully');
    }

    public function createNftClaim(Request $request)
    {
        $nft = new NFT\NFTMint();
        $nft->nft_title = $request->name ?? '';
        $nft->nft_symbol = $request->symbol ?? '';
        $nft->nft_uri = $request->uri ?? '';
        $nft->seed = $request->seed ?? '';
        $nft->address_nft = $request->address_nft ?? '';
        $nft->address_organizer = $request->address_organizer ?? '';
        $nft->secret_key = $request->secret_key ?? '';
        $nft->type = $request->type;
        $nft->task_id = $request->task_id;

        $nft->save();

        if ($nft) {
            return [
                'code' => 200,
                'msg' => 'success'
            ];
        }

        return null;
    }


    public function updateNftClaim(Request $request)
    {
        $nft = NFT\NFTMint::find($request->nft_id);
        
        if (auth()->user() == null) {
            // check login and auth
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                $newUser = new User();
                $newUser->name = $request->email;
                $newUser->email = $request->email;
//                $newUser->wallet_address = $request->address;
                $newUser->email_verified_at = now();
                $newUser->status = USER_ACTIVE;
                $newUser->save();

                Auth::login($newUser);
            }
        }
        if (!empty($nft)) {

            // update nft
            $nft->status = 3;
            $nft->save();

            $userNft = new UserNft();
            $userNft->user_id = \auth()->user()->id;
            $userNft->nft_mint_id = $nft->id;
            $userNft->type = $nft->type;
            $userNft->booth_id = $nft->booth_id;
            $userNft->task_id = $nft->task_id;
            $userNft->digest = $request->digest ?? '';
            $userNft->save();
//            $nft = NFT\NFTMint::find($request->nft_id);
//            $nft->status = 3;
//            $nft->save();
        }
        return [
            "code" => 200
        ];
    }

    public function updateSessionBoothClaim(Request $request)
    {
        $nft = NFT\NFTMint::find($request->nft_id);
        
        if (auth()->user() == null) {
            // check login and auth
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                $newUser = new User();
                $newUser->name = $request->email;
                $newUser->email = $request->email;
//                $newUser->wallet_address = $request->address;
                $newUser->email_verified_at = now();
                $newUser->status = USER_ACTIVE;
                $newUser->save();

                Auth::login($newUser);
            }
        }
        if (!empty($nft)) {

            // update nft
            $nft->status = 3;
            $nft->save();

            $userNft = new UserNft();
            $userNft->user_id = \auth()->user()->id;
            $userNft->nft_mint_id = $nft->id;
            $userNft->type = $nft->type;
            $userNft->booth_id = $nft->booth_id;
            $userNft->task_id = $nft->task_id;
            $userNft->digest = $request->digest ?? '';
            $userNft->save();
//            $nft = NFT\NFTMint::find($request->nft_id);
//            $nft->status = 3;
//            $nft->save();
        }
        return [
            "code" => 200
        ];
    }

    public function uploadImageNft(Request $request)
    {
//        $path = \Storage::disk('s3')->put('images/originals', $request->file, 'public');
        $fullPath = $this->getFullPath();
        $imageRequest = $request->file;
        $filenameWithExt = $imageRequest->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $fileNameToStore = $filename.'_'.str_shuffle(time());
        $image = $imageRequest->storeOnCloudinaryAs($fullPath, $fileNameToStore);
        $filePath = $image->getPublicId().'.'.$image->getExtension();
        $cloudinaryname = config('cloudinary.cloud_app');
        $baseUrl = "http://res.cloudinary.com/$cloudinaryname/image/upload/";
        $path = $filePath != false ? $baseUrl.$filePath : 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg';
        return [
            "code" => 200,
            'path' => $path
        ];
    }

    public function getFullPath()
    {
        /*Path Date Year*/
        $pathDateYear = date('Y');
        /*Path Date Month*/
        $pathDateMonth = date('m');
        /*Path Date Day*/
        $pathDateDay = date('d');

        return 'public/images/'.$pathDateYear.$pathDateMonth;
    }
}
