<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event\TaskEventDetail as EventTaskEventDetail;
use App\Models\NFT;
use App\Models\NFT\UserNft;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\NFT\TicketNftMint;
use App\Models\NFT\TaskEventDetailNftMint;
use App\Models\TaskEventDetail;
use App\Models\TicketCollection;
use \DB;

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
        $data = $request->only(['digest','nft_mint_id','task_id']);
        
        $validator = [
           
            'digest'=>[
                'required',
                'min:1',
                'string',
            ],
            'nft_mint_id'=>[
                'required',
                'min:1',
                'string',
            ],
            
            'task_id'=>[
                'required',
                'min:1',
                'string',
            ],
        ];

        $messages = [

        ];

        $validator = Validator::make($data, $validator,$messages);

        // validate data
        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' =>  $validator->messages()->first()
            ], 400);
        }

        $userClaimtNft = UserNft::where('user_id',auth()->user()->id)
            ->whereNull('booth_id')
            ->whereNull('session_id')
            ->where('task_id',$data['task_id'])
            ->where('nft_mint_id',$data['nft_mint_id'])
            ->first();
       
        // nếu user này chưa claim thì claim có rồi thì thôi
        if(!empty($userClaimtNft)){

            return response()->json([
                'status' => false,
                'message' =>  'Your User Claimed NFT already'
            ], 400);
        }
        
        $responses = null;

        DB::transaction(function () use ($data, &$responses) {

            $data['type'] = 1;
            $data['id'] = Str::uuid();
            $data['user_id'] = auth()->user()->id;

            $ticketNftMint = TicketNftMint::where('id', $data['nft_mint_id'])->first();

            $ticketCollection = TicketCollection::where('id', $ticketNftMint['ticket_id'])->first();

           
            $ticketCollection->available_amount = $ticketCollection->available_amount + 1;
            
            if($ticketCollection->available_amount > $ticketCollection['amount']){

                $responses = [
                    'status' => false,
                    'message' => 'The number of tickets has exceeded the specified number'
                ];
                return;
            }

            //lưu người dùng nào claim
            $createUserNft = UserNft::create($data);

            $ticketCollection->timestamps = false; // Disable updating the updated_at column
            
            $ticketCollection->save();

            // Gán kết quả trả về vào biến $response
            $responses = [
                'status' => true,
                'message' => 'Your User Claim NFT success',
                'data' => $createUserNft
            ];
            
        });

        return response()->json($responses, 200);
    }

    public function updateSessionBoothClaim(Request $request)
    {
        $data = $request->only(['booth_id','digest','nft_mint_id','session_id','task_id']);
        
        $validator = [
            'booth_id'=>[
            ],
            'digest'=>[
                'required',
                'min:1',
                'string',
            ],
            'nft_mint_id'=>[
                'required',
                'min:1',
                'string',
            ],
            'session_id'=>[
            ],
            'task_id'=>[
                'required',
                'min:1',
                'string',
            ],
        ];

        $messages = [

        ];

        $validator = Validator::make($data, $validator,$messages);

        // validate data
        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' =>  $validator->messages()->first()
            ], 400);
        }

        if(isset($data['session_id'])){

            $userClaimtNft = UserNft::where('user_id',auth()->user()->id)
                ->where('nft_mint_id', $data['nft_mint_id'])
                ->where('session_id', $data['session_id'])
                ->where('task_id', $data['task_id'])->first();
            $data['type'] = 2;

        }
        
        if(isset($data['booth_id'])){
            
            $userClaimtNft = UserNft::where('user_id',auth()->user()->id)
                ->where('nft_mint_id', $data['nft_mint_id'])
                ->where('booth_id', $data['booth_id'])
                ->where('task_id', $request['task_id'])->first();
            $data['type'] = 3;
        }
       
        // nếu user này chưa claim thì claim có rồi thì thôi
        if(!empty($userClaimtNft)){

            return response()->json([
                'status' => false,
                'message' =>  'Your User Claimed NFT already'
            ], 400);
        }
           
        $responses = null;
        
        DB::transaction(function () use ($data, &$responses) {

            $data['id'] = Str::uuid();

            $data['user_id'] = auth()->user()->id;

            $taskEventDetailNftMint = TaskEventDetailNftMint::where('id', $data['nft_mint_id'])->first();
            
            $eventTaskEventDetail = EventTaskEventDetail::where('id', $taskEventDetailNftMint['task_event_detail_id'])->first();

           
            $eventTaskEventDetail->avaliable_amount = $eventTaskEventDetail->avaliable_amount + 1;
            
            //claim hết số lượng thì hiển thị thông báo
            if($eventTaskEventDetail->avaliable_amount > $eventTaskEventDetail['amount']){

                $responses = [
                    'status' => false,
                    'message' => 'The number of tickets has exceeded the specified number'
                ];
                return;
            }

            //lưu người dùng nào claim
            $createUserNft = UserNft::create($data);

            $eventTaskEventDetail->timestamps = false; // Disable updating the updated_at column
            
            $eventTaskEventDetail->save();

            // Gán kết quả trả về vào biến $response
            $responses = [
                'status' => true,
                'message' => 'Your User Claim NFT success',
                'data' => $createUserNft
            ];
            
        });
        
        return response()->json($responses, 200);
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
