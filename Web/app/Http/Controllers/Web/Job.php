<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Mail, Redirect, Session};
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserForm;
use App\Mail\VerifyCodeEmail;
use App\Models\Event\{
    EventUserTicket,
    TaskEvent,
    TaskEventDetail,
    UserJoinEvent,
    UserEvent,
    UserCode,
};
use App\Models\{NFT\NFTMint, NFT\UserNft, Task, User, TravelGame, Sponsor, SponsorDetail, UserSponsor};
use App\Services\{CodeHashService, TaskService};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use \GuzzleHttp\Client;

class Job extends Controller
{
    public function __construct(
        private CodeHashService $codeHashService,
        private TaskEventDetail $eventDetail,
        private TaskEvent $taskEvent,
        private UserJoinEvent $joinEvent,
        private Task $task,
        private TaskEvent       $eventModel,
        private UserCode $userCode,
        private UserJoinEvent   $taskDone,
        private Sponsor $sponsor,
        private SponsorDetail $sponsorDetail,
        private UserSponsor $userSponsor,
        private UserEvent $userEvent,
        private TravelGame $travelGame,
        private EventUserTicket $eventUserTicket,
        private TaskService $taskService,
    ) {
        // Code
    }

    // GET
    // Url: http://event.plats.test/events/code?type=event&id=tuiLOSvRxDUZk2cNTMu5LoA8s4VXxoO4fXe
    public function index(Request $request)
    {
        $data = $request->only(['type','id']);

        //valid credential
        $validator = Validator::make($data, [
            'type' => [
                'required',
                'string',
                'min:1',
            ],
            'id' => [
                'required',
                'string',
                'min:1',
            ],
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            abort(404);
        }

        //try {
//            if (Auth::guest()) {
//                $time = Carbon::now()->timestamp;
//                $user = User::create([
//                    'name' => 'Guest-'.$time,
//                    'email' => 'guest-'.$time.'@gmail.com',
//                    'password' => '12345678a@#',
//                    'role' => GUEST_ROLE,
//                    'confirmation_code' => null,
//                    'email_verified_at' => now()
//                ]);
//
//                Auth::login($user, true);
//            }

            $user = Auth::user();
            $code = $request->input('id');
            $event = $this->eventDetail->whereCode($code)->first();

            $taskEvent = $this->taskEvent->find($event->task_event_id);
            $task = $this->task->find($taskEvent->task_id);

            // Check NFT
            $eventIds = $this->taskEvent->whereTaskId($task->id)->pluck('id')->toArray();
            $countJobOne = $this->joinEvent
                ->whereUserId($user->id)
                ->whereIn('task_event_id', $eventIds)
                ->count();

            if ($countJobOne >= 1) {
                if ($event->nft_link) {
                    session()->put('nft-'.$user->id, [
                        'url' => $event->nft_link,
                        'nft' => true
                    ]);
                }
            }
            // End Check NFT
            if (!$event) {
                notify()->error('Không tồn tại');;
                return redirect()->route('web.home');
            }
          
            if(!$user) {
                session()->put('guest', [
                    'id' => $code,
                    'type' => 'job'
                ]);
                notify()->error('Vui lòng login để hoàn thành.');

                return redirect()->route('web.formLoginGuest');
            }
        
            if ($task) {
                $checkUserEvent = $this->userEvent
                    ->whereTaskId($task->id)
                    ->whereUserId($user->id)
                    ->exists();

//                if (!$checkUserEvent) {
//                    $this->userEvent->create([
//                        'user_id' => $user->id,
//                        'task_id' => $task->id
//                    ]);
//                }
            }
         
            //không có mã qr hợp lệ
            if ($event && !$event->status) {

                notify()->error('Job locked!');
                return redirect()->route('job.getJob', [
                    'code' => $event->code
                ])->with('error', "Job locked!");
            } else {
                
                notify()->success('Scan QR code success');
                return $this->getJob($request,$event->code);
                // return redirect()->route('job.getJob', [
                //     'code' => $event->code
                // ]);
            }
            
            return redirect()->route('job.getTravelGame', [
                'id' => $task->code,
                'task_id' => $task->code
            ]);
        //} catch (\Exception $e) {
            //notify()->error('Có lỗi xảy ra');
            //return redirect()->route('web.home');
        //}
    }

    // TODO
    // method: GET
    // url: http://event.plats.test/quiz/tuiLOSvRxDUZk2cNTMu5LoA8s4VXxoO4fXe
    public function getJob(Request $request, $code) {
        
        try {
            $detail = $this->eventDetail->whereCode($code)->first();

            // check data
            if (empty($detail)) {

                notify()->error('Errors detail');
                return redirect()->route('web.home');
            }

            $taskEvent = $this->taskEvent->find($detail->task_event_id);

            // check data
            if (empty($taskEvent)) {

                notify()->error('Errors taskEvent');
                return redirect()->route('web.home');
            }

            $taskId = $taskEvent->task_id;

            // check data
            if (empty($taskId)) {

                notify()->error('Errors taskId');
                return redirect()->route('web.home');
            }

            $task = $this->task->find($taskId);

            // check data
            if (empty($task)) {

                notify()->error('Errors task');
                return redirect()->route('web.home');
            }

            $user = Auth::user();

            $checkJoin = $this->eventUserTicket
                ->whereTaskId($taskId)
                ->whereUserId($user->id)
                ->exists();

            //không có user thì tạo 1 user mới ngẫu nhiên
            if (!$checkJoin) {
                $this->eventUserTicket->create([
                    'name' => $user->name,
                    'phone' => $user->phone ?? '092384234',
                    'email' => $user->email,
                    'task_id' => $taskId,
                    'user_id' => $user->id,
                    'is_checkin' => true,
                    'hash_code' => Str::random(35)
                ]);
            }
       
            $checkEventJob = $this->joinEvent
                ->where('task_event_detail_id', $detail->id)
                ->whereUserId($user->id)
                ->exists();

            if (!$checkEventJob) {

                if (!$detail->status) {

                    notify()->error('QR code locked');

                    return redirect()->route('web.jobEvent', [
                        'id' => $task->code,
                        'type' => $taskEvent->type
                    ]);

                } else {

                    $isImportant = $taskEvent->type == 0 ? $detail->is_question : $detail->is_required;

                    $this->joinEvent->create([
                        'task_event_detail_id' => $detail->id,
                        'travel_game_id' => $detail->travel_game_id,
                        'user_id' => $user->id,
                        'task_id' => $taskId,
                        'task_event_id' => $detail->task_event_id,
                        'type' => $taskEvent->type,
                        'is_important' => $isImportant
                    ]);
                }
            }

            $eventIds = $this->taskEvent->whereTaskId($taskId)->pluck('id')->toArray();

            $countJobOne = $this->joinEvent
                ->whereUserId($user->id)
                ->whereIn('task_event_id', $eventIds)
                ->count();

            // Gen code
            $session = $this->taskEvent->whereTaskId($taskId)->whereType(TASK_SESSION)->first();
            $travelSessionIds = $this->eventDetail
                ->select('travel_game_id')
                ->distinct()
                ->whereTaskEventId($session->id)
                ->pluck('travel_game_id')
                ->toArray();
            // End

            $booth = $this->taskEvent->whereTaskId($taskId)->whereType(TASK_BOOTH)->first();
            $travelBootsIds = $this->eventDetail
                ->select('travel_game_id')
                ->distinct()
                ->whereTaskEventId($booth->id)
                ->pluck('travel_game_id')
                ->toArray();

            if ($taskEvent->type == 0) {
                notify()->success('Scan QR code success');
            }
          
            // notify()->success('Scan QR code success');
            if ($countJobOne <= 1) {
                
                return $this->getTravelGame($request,$taskId);

            }

            // Gen code
            $this->taskService->genCodeByUser($user->id, $taskId, $travelSessionIds, $travelBootsIds, $session->id, $booth->id);
            
            // notify()->success('Scan QR code success');
            return redirect()->route('web.jobEvent', [
                'id' => $task->code,
                'type' => $taskEvent->type
            ]);


        } catch (\Exception $e) {
            notify()->error('Có lỗi xảy ra');
            return redirect()->route('web.home');
        }

        return view('web.events.quiz', [
            'detail' => $detail,
            'task_code' => $task->code,
            'task_id' => $taskId,
            'count' => $countJobOne
        ]);
    }

    /**
     * List travel game
     *
     * method: GET
     * url: http://event.plats.test/info/{task_id}
     * author: suoi
     */
    public function getTravelGame(Request $request, $taskId)
    {   
       
        try {
            $event = $this->task->find($taskId);
            //$checkUserGetCode = $this->checkUserGetCode($request, $taskId);
          
            $travelSessions = [];
            $session = $this->taskEvent->whereTaskId($taskId)->whereType(TASK_SESSION)->first();
            $travelSessionIds = $this->eventDetail
                ->select('travel_game_id')
                ->distinct()
                ->whereTaskEventId($session->id)
                ->pluck('travel_game_id')
                ->toArray();

            $travelBoots = [];
            $booth = $this->taskEvent->whereTaskId($taskId)->whereType(TASK_BOOTH)->first();
            $travelBootsIds = $this->eventDetail
                ->select('travel_game_id')
                ->distinct()
                ->whereTaskEventId($booth->id)
                ->pluck('travel_game_id')
                ->toArray();

            $travelSessions = $this->travelGame
//                ->whereIn('id', $travelSessionIds)
                ->orderBy('created_at', 'desc')
                ->get();
//dd($travelSessions);
            $travelBooths = $this->travelGame->whereIn('id', $travelBootsIds)->get();

            // Start
            $user = Auth::user();
            $sessionNFT = session()->get('nft-'.$user->id);
            $flag = session()->get('u-'.$user->id);
            $flagU = 0;
            if ($flag == 1 && Str::contains($user->email, 'guest')) {
                $flagU = 1;
            }
            //$userCode = new App\Models\Event\UserCode();

            // $this->taskService->genCodeByUser($userId, $taskId, $travelSessionIds, $travelBootsIds, $session->id, $booth->id);
            $eventSession = $this->eventModel->whereTaskId($taskId)->whereType(TASK_SESSION)->first();

            $eventBooth = $this->eventModel->whereTaskId($taskId)->whereType(TASK_BOOTH)->first();

            $sessions = $this->eventDetail->whereTaskEventId($eventSession->id)
                //->orderBy('sort', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            $totalCompleted = 0;

            foreach ($sessions as $session) {
                $travel = $this->travelGame->find($session->travel_game_id);
                $job = $this->taskDone
                    ->whereUserId($user->id)
                    ->whereTaskEventDetailId($session->id)
                    ->first();

                //dd($session->id);
                //9ae45be7-b580-452e-91b6-05eda488b4ce

                /*dd($this->taskDone
                    ->whereUserId($user->id)
                    ->where('task_id', $taskId)
                    //->whereTaskEventDetailId($session->id)
                    ->count());*/
                $isDoneTask = $this->checkDoneJob($session->id);
                if ($isDoneTask) {
                    $totalCompleted++;
                }
                $sessionDatas[] = [
                    'id' => $session->id,
                    'travel_game_id' => $session->travel_game_id ?? '',
                    'travel_game_name' => $travel->name ?? '',
                    'user_id' => $request->user()->id ?? '',
                    'name' => $session->name ?? '',
                    'desc' => $session->description ?? '',
                    'date' => $job ? Carbon::parse($job->created_at)->format('Y-m-d') : '',
                    'time' => $job ? Carbon::parse($job->created_at)->format('H:i') : '',
                    'required' => $session->is_required ?? '',
                    'created_at' => $session->created_at ?? '',
                    'flag' =>$isDoneTask ?? ''
                ];
            }
         
            $groupSessions = [];
            $groupBooths = [];
            foreach ($sessionDatas as $item) {
                $groupSessions[$item['travel_game_id']][] = $item;
            }
            //Create code if $totalCompleted >=6
            $maxSession = 1;

            if ($totalCompleted >= $maxSession) {
                //$this->taskService->genCodeByUser($user->id, $taskId, $travelSessionIds, $travelBootsIds, $session->id, $booth->id);

                // $codes = $userCode->where('user_id', $userId)
                //                                    ->where('travel_game_id', $session->id)
                //                                    ->where('task_event_id', $session_id)
                //                                    ->where('type', 0)
                //                                    ->pluck('number_code')
                //                                    ->implode(',');
               /* $this->userCode->create([
                    'user_id' => $userId,
                    'task_event_id' => $sEventId,
                    'travel_game_id' => $tId,
                    'type' => 0,
                    'number_code' => $maxSession + 1,
                    'color_code' => randColor()
                ]);*/
                
                //Check user code not exists
                $checkCode = $this->userCode
                    ->whereUserId($user->id)
                    ->whereTaskEventId($session->id)
                    ->where('travel_game_id', $session->travel_game_id)
                    ->where('type', 0)
                    ->exists();
                if (!$checkCode) {
                    $max = $this->userCode
                        ->whereTaskEventId($session->id)
                        ->where('travel_game_id', $session->travel_game_id)
                        ->max('number_code');

                    $maxCode =  $max + 1;
                    //Check if  $maxCode < 100 then add 100
                    if ($maxCode < 100) {
                        $maxCode = $maxCode;
                    }

                    $this->userCode->create([
                        'user_id' => $user->id,
                        'task_event_id' => $session->id,
                        'travel_game_id' => $session->travel_game_id,
                        'type' => 0,
                        'number_code' => $maxCode,
                        'color_code' => randColor()
                    ]);
                }


                //Send Code to User Email
            }

        } catch (\Exception $e) {
            abort(404);
        }

        //dd($session->id);
        //dd($groupSessions);
        //array:1 [▼ // app\Http\Controllers\Web\Job.php:416
        //  $event->id => array:8 [▼
        //    0 => array:10 [▼
        //      "id" => "9ae45be7-b580-452e-91b6-05eda488b4ce"
        //      "travel_game_id" => $event->id
        //      "travel_game_name" => "Session"
        //      "user_id" => "9ae6850b-e2e6-4a3e-9024-237f992b3426"
        //      "name" => "DOANH NGHIỆP BÀI BẢN - VIỆT NAM PHỤC HỒI"
        //      "desc" => null
        //      "date" => "2023-12-21"
        //      "time" => "15:50"
        //      "required" => 0
        //      "flag" => true
        //    ]
        //    1 => array:10 [▶]
        //    2 => array:10 [▶]
        //    3 => array:10 [▶]
        //    4 => array:10 [▶]
        //    5 => array:10 [▶]
        //    6 => array:10 [▶]
        //    7 => array:10 [▼
        //      "id" => "9ae45be8-18e5-496e-aad0-661388e7970b"
        //      "travel_game_id" => $event->id
        //      "travel_game_name" => "Session"
        //      "user_id" => "9ae6850b-e2e6-4a3e-9024-237f992b3426"
        //      "name" => "Talkshow PHIÊN 2  - CƠ HỘI - VƯƠN TẦM QUỐC TẾ"
        //      "desc" => null
        //      "date" => ""
        //      "time" => ""
        //      "required" => 0
        //      "flag" => false
        //    ]
        //  ]
        //]

        //Sort $groupSessions item 5  to 7

        // Lấy thông tin của các phần tử cần đổi vị trí
        $item5 = $groupSessions[$item['travel_game_id']][5] ?? null;
        $item6 = $groupSessions[$item['travel_game_id']][6] ?? null;
        $item7 = $groupSessions[$item['travel_game_id']][7] ?? null;

        // Kiểm tra xem các phần tử có tồn tại không
        if ($item5 !== null && $item6 !== null && $item7 !== null) {
            // Xóa các phần tử cần đổi vị trí
            unset(
                $groupSessions[$item['travel_game_id']][5],
                $groupSessions[$item['travel_game_id']][6],
                $groupSessions[$item['travel_game_id']][7]
            );

            // Thêm các phần tử vào mảng tạm thời
            $tempArray = [$item5, $item6, $item7];

            // Chèn lại các phần tử ở vị trí mới
            array_splice($groupSessions[$item['travel_game_id']], 5, 0, [$tempArray[2]]);
            array_splice($groupSessions[$item['travel_game_id']], 6, 0, [$tempArray[0]]);
            array_splice($groupSessions[$item['travel_game_id']], 7, 0, [$tempArray[1]]);
        }

        $checkNftMint = UserNft::where([
            'booth_id'=> $booth->id,
            'session_id' => $session->id,
            'user_id' => \auth()->user()->id,
        ])->first();
            
        $qrCode = '';
        if ($checkNftMint) {
            $qrCode = base64_encode(QrCode::format('png')->size(250)->generate(route('nft.claimAction', $checkNftMint->id)));
        } else {
            
            // get nft claim
            $nft = NFTMint::where('status', NFTMint::ACTIVE)->whereIn(
                'type', [2, 3]
            )->first();

                
            if ($nft) {
               
                $nft->status = NFTMint::SENDING;
                $nft->save();
              
                // create user mint
                // save nft
                // $userNft = new UserNft();
                // $userNft->user_id = \auth()->user()->id;
                // $userNft->nft_mint_id = $nft->id;
                // $userNft->type = $nft->booth_id;
                // $userNft->booth_id = $booth->id;
                // $userNft->session_id = $session->id;
                // $userNft->save();
             
                $qrCode = base64_encode(QrCode::format('png')->size(250)->generate(route('nft.claimAction', $nft->id)));
            }
            //d($checkUserGetCode);
            
        }
     
        $nftMint =  NFTMint::where('session_id',$session->id ?? '')->first();
        
        $nftUserMint =  UserNft::where([
                'session_id'=>$session->id ?? '',
                'user_id'=>auth()->user()->id,
        ])->first();
         $data = [
            'event' => $event,
            'totalCompleted' => $totalCompleted,
            'session_id' => $session->id,
            'booth_id' => $booth->id,
            'travelSessions' => $travelSessions,
            'travelBooths' => $travelBooths,
            'url' => $sessionNFT && $sessionNFT['url'] ? $sessionNFT['url'] : null,
            'nft' => $sessionNFT && $sessionNFT['nft'] ? 1 : 0,
            'flagU' => $flagU,
            'qrCode' => $qrCode,
            'checkNftMint' => $checkNftMint,
            'groupSessions' => ($groupSessions),
            'nftMint'=>$nftMint,
            'nftUserMint'=>$nftUserMint
        ];
        return view('web.events.travel_game', $data);
    }
    //Check user get code when have at  tend 6/8 session in booth
    public function checkUserGetCode(Request $request, $taskId)
    {
        try {
            $userId = Auth::user()->id;
            $task = $this->task->whereCode($taskId)->first();
            $eventSession = $this->eventModel->whereTaskId($task->id)->whereType(TASK_SESSION)->first();
            $eventBooth = $this->eventModel->whereTaskId($task->id)->whereType(TASK_BOOTH)->first();
            $sessions = $this->eventDetail->whereTaskEventId($eventSession->id)->orderBy('sort', 'asc')->get();
            $booths = $this->eventDetail->whereTaskEventId($eventBooth->id)->orderBy('sort', 'asc')->get();
            $countSession = 0;
            $countBooth = 0;
            foreach ($booths as $booth) {
                $countBooth+= $this->countBootDone($booth->id);
            }
            dd($countBooth);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    private function countBootDone($eventDetailId){
        $userId = Auth::user()->id;
        $status = $this->taskDone
           ->whereUserId($userId)
           ->whereTaskEventDetailId($eventDetailId)
           ->count();

       return $status;
    }
    private function checkDoneJob($eventDetailId)
    {
        $userId = Auth::user()->id;


        return $this->taskDone
            ->whereUserId($userId)
            ->whereTaskEventDetailId($eventDetailId)
            ->exists();
    }

    public function removeNft(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            session()->forget('nft-'.$userId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    // New sponsor
    // method: GET
    // URL: /sponsor/new
    public function newSponsor(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $infoParams = $request->session()->get('sponsor-'.$userId);
            if (!$request->session()->has('sponsor-'.$userId)) {
                $request->session()->put('sponsor-'.$userId, [
                    'task_id' => $request->input('event_id'),
                    'type' => $request->input('type'),
                    'amount' => $request->input('amount'),
                    'detail_id' => $request->input('detail_id')
                ]);
                $infoParams = $request->session()->get('sponsor-'.$userId);
            }

            $event = $this->task->find($infoParams['task_id']);
            if (!$event) { abort(404); }

            $sponsor = $this->sponsor->whereTaskId($event->id)->first();
            $detail = $this->sponsorDetail->find($infoParams['detail_id']);

            if (!$sponsor || !$detail) { abort(404); }
        } catch (\Exception $e) {
            abort(404);
        }

        return view('web.events.new_sponsor', [
            'event' => $event,
            'sponsor' => $sponsor,
            'detail' => $detail
        ]);
    }

    // Save sponsor
    // method: POST
    // URL: /sponsor/pay
    public function saveSponsor(Request $request)
    {
        try {
            $taskId = $request->input('task_id');
            $userId = Auth::user()->id;
            $this->userSponsor->create([
                'user_id' => $userId,
                'task_id' => $taskId,
                'sponsor_id' => $request->input('sponsor_id'),
                'sponsor_detail_id' => $request->input('sponsor_detail_id'),
                'amount' => (int) $request->input('amount'),
                'note' => $request->input('note'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'message' => 'Error: ' . $e->getMessage()
                ]
            ], 500);
        }

        return response()->json([
            'data' => [
                'message' => 'Successful'
            ]
        ], 200);
    }

    // Save sponsor
    // method: POST
    // URL: /zkp/get
    public function zkp(Request $request){
        $data = $request->only(['jwt','extendedEphemeralPublicKey','jwtRandomness','maxEpoch','salt','keyClaimName']);
        $validator = [

            'jwt'=>[
                'required',
                'min:1',
                'string',
            ],
            'extendedEphemeralPublicKey'=>[
                'required',
                'min:1',
                'string',
            ],
            'jwtRandomness'=>[
                'required',
                'min:1',
                'string',
            ],
            'maxEpoch'=>[
                'required',
                'min:1',
                'string',
            ],
            'salt'=>[
                'required',
                'min:1',
                'string',
            ],
            'keyClaimName'=>[
                'required',
                'min:1',
                'string'
            ]
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
        $client = new Client();
    
        $response = $client->post('https://prover-dev.mystenlabs.com/v1', [
            'headers' => [
            'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        try {
            return $response->getBody();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()->first()
            ], 500);
        }
    }
}
