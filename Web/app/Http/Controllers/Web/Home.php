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
use MagicAdmin\Exception\RequestException;

class Home extends Controller
{
    public function __construct(
        private TaskEvent       $eventModel,
        private Task            $task,
        private User            $user,
        private UserService     $userService,
        private Sponsor         $sponsor,
        private UserJoinEvent   $taskDone,
        private TaskEventDetail $eventDetail,
        private EventUserTicket $eventUserTicket,
        private EventService    $eventService,
        private TaskService     $taskService,
        private TravelGame      $travelGame,
        private UserCode        $userCode,
    )
    {
    }

    public function index(Request $request)
    {

        try {

            $limit = $request->get('limit') ?? 4;

            $user = Auth::user();

            // Sử dụng mối quan hệ để lấy thông tin
            $eventsQuery = Task::where('status', 1)
                ->whereNotNull('name')
                ->whereNotNull('description')
                ->orderBy('id', 'DESC');

            //lấy thông tin user tham gia sự kiện
            if (!empty($user)) {
                $eventsQuery = $eventsQuery->whereHas('userEvents', function ($query) use ($user) {
                    $query->where('user_id', $user['id']);
                });
            }

            $eventsPendings = $eventsQuery->paginate(4);

            //lấy thông tin các sự kiện
            $events = $this->taskService->search([
                'limit' => $limit,
                'type' => 1,
                'status' => 1
            ]);

        } catch (\Exception $e) {
            Log::error('Errors: ' . $e->getMessage());
        }

        $data = [
            'events' => $events,
            'eventsPendings' => $eventsPendings
        ];

        return view('web.home', $data);
    }

    //createCrowdSponsor
    public function createCrowdSponsor(Request $request)
    {
        $input = $request->all();
        try {
            $user = Auth::user();
            $task_id = $request->get('task_id');
            $task = $this->task->find($task_id);
            $sponsor = $this->sponsor->whereTaskId($task_id)->first();
            $checkSponsor = session()->get('sponsor-' . optional($user)->id);

            if ($request->session()->has('sponsor-' . optional($user)->id)) {
                $request->session()->forget('sponsor-' . optional($user)->id);
            }
        } catch (\Exception $e) {
            notify()->error('Sự kiện không tồn tại.');
            return redirect()->route('web.home');
        }
        //localhost:5173/payment-crowdsponsor?name="tranchinh"
        //&&price_type="xin chao moi nguoi toi la tran van chinh"
        //&&total_price=5&&blockchain="alphe"&&end_at="urlimage"&&des=”mota”

        //Data send
        $timeEndExpire = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
        $dataSend = [
            'event_id' => $task->id,
            'name' => $user->name,
            //'price_type' => $request->get('price_type', 'AZERO'),
            'price_type' => $request->get('price_type', 'ACA'), //Update 12/01/2024
            'total_price' => $request->get('price', 100),
            //'blockchain' => $request->get('blockchain', 'Aleph Zero'),
            'blockchain' => $request->get('blockchain', 'Acala'),
            'end_at' => $request->get('end_at', $timeEndExpire),
            'des' => $request->get('des', 'Crowd Sponsor'),
        ];


        //Callback URL
        $callback_url = route('payment-success', ['event_id' => $dataSend['event_id']]);

        $dataNFT['callback_url'] = $callback_url;
        //MD5 Hash
        $dataNFT['hash'] = md5(json_encode($dataSend));

        //Create Payment Link with data. Set for router payment-request
        $paymentLink = route('web.paymentCrowdSponsor', $dataSend);
        //Url Payment
        //$urlPayment = 'http://localhost:5173';
        $urlPayment = 'https://platsevent.web.app';
        //Replace $paymentLink with $urlPayment
        $paymentLink = str_replace(url('/'), $urlPayment, $paymentLink);


        //Return Payment Link
        return redirect($paymentLink);
    }

    //paymentCrowdSponsor
    public function paymentCrowdSponsor(Request $request)
    {
        $input = $request->all();

        return response()->json([
            'status' => true,
            'message' => 'Payment Sponsor Request Created Successfully',
            'data' => $input
        ]);
    }
    //paymentSuccess
    //Return view payment success
    public function paymentSuccess(Request $request)
    {
        $input = $request->all();
        //type
        $type = $request->get('type') ?? 1;

        $event_id = $input['event_id'] ?? null;
        $event = $this->task->find($event_id);
        $user = Auth::user();
        $sponsor = $this->sponsor->whereTaskId($event_id)->first();
        $checkSponsor = session()->get('sponsor-' . optional($user)->id);

        if ($request->session()->has('sponsor-' . optional($user)->id)) {
            $request->session()->forget('sponsor-' . optional($user)->id);
        }
        //Save Event Sponsor

        //Date now payment
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

        $text = 'Thanks for sponsoring the event.';
        $textButton = 'View Invoice';
        if ($type == 2) {
            $textButton = 'Download Ticket';
            $text = 'Thanks for ordering a ticket to the event.
        We will send you a confirmation email with your ticket details shortly.';
        };


        return view('home.payment-success', [
            'event' => $event,
            'user' => $user,
            'text' => $text,
            'textButton' => $textButton,
            'dateNow' => $dateNow,
            'sponsor' => $sponsor,
            'checkSponsor' => $checkSponsor,
        ]);
    }

    public function isResult(Request $request, $id)
    {
        try {
            if (env('APP_ENV') == 'production') {
                $id = '9a131bf1-d41a-4412-a075-599e97bf6dcb';
            } else {
                $id = '9a0ad18b-de5b-4881-a323-f141420713ab';
            }
            $user = Auth::user();

            if ($user && Str::contains($user->email, 'guest')) {
                session()->put('u-' . $user->id, 1);
            }
        } catch (\Exception $e) {
            return redirect()->route('job.getTravelGame', [
                'task_id' => $id
            ]);
        }

        return redirect()->route('job.getTravelGame', [
            'task_id' => $id
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        if (empty($user)) {
            return redirect()->route('web.formLogin');
        }
        $show_message = $request->get('sucess_checkin') ?? 0;
        //download_ticket
        $download_ticket = $request->get('download_ticket') ?? 0;
        $has_checkin = 0;
        //url download ticket
        $url_download_ticket = route('ticket.pdf', ['id' => $id]);
        try {
            $user = Auth::user();

            $event = $this->taskService->find($id);
            //Check event is null
            if (!$event) {
                notify()->error('Sự kiện không tồn tại.');

                return redirect()->route('web.home');
            }
            //Increase view
            /*$this->taskService->update($id, [
                'view_count' => $event->view + 1
            ]);*/
            //check_in
            $check_in = $request->get('check_in') ?? 0;

            if ($user && $check_in) {
                //Need check user is verify email
                /*if (!$user->email_verified_at) {
                    notify()->error('Vui lòng xác nhận email để tham gia sự kiện.');

                    return redirect()->route('web.home');
                }*/
                $emailUser = $user->email;
                //Check email valid
                if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
                    notify()->error('Vui lòng xác nhận email để tham gia sự kiện.');

                    //return redirect()->route('web.events.show', $id);
                }
                //Check string length > 50
                if (strlen($emailUser) > 50) {
                    notify()->error('Vui lòng xác nhận email để tham gia sự kiện.');

                    //return redirect()->route('web.events.show', $id);
                }

                //Mail::to($user)->send(new SendNFTMail($event));
                $options = array(
                    'verify_url' => 'http://gotohere.com',
                    'image_url' => '',
                    'event_name' => $event->name,
                    'event_description' => $event->description,
                    'event_url' => route('web.events.show', $event->id),
                    'event_date' => $event->start_date,
                    'event_time' => $event->start_time,
                    'event_location' => $event->address,
                    'invoice_id' => '10087866',
                    'invoice_total' => '100.07',
                    'download_link' => 'https://platsevent.web.app/claim-nft?id=' . $event->id,
                );


                Mail::to($user)->send(new \App\Mail\ThankYouCheckInNFT($user, $options));

                //Mail::to($user)->send(new OrderCreated());
                $recipientEmail = $user->email;
                $userName = $user->name;
                $senderName = 'Plats Event';
                $nftModel = NFT::query()->where('task_id', $id)->first();
                $nftName = '';
                $nftDescription = '';
                $nftUrl = '';
                if ($nftModel) {
                    $nftName = $nftModel->name;
                    $nftDescription = $nftModel->description;
                    $nftUrl = $nftModel->url;
                }


                //Mail::to($recipientEmail)->send(new NFTNotification($userName, $senderName, $nftName, $nftDescription, $nftUrl));

            }
            $sponsor = $this->sponsor->whereTaskId($id)->first();
            $checkSponsor = session()->get('sponsor-' . optional($user)->id);

            if ($request->session()->has('sponsor-' . optional($user)->id)) {
                $request->session()->forget('sponsor-' . optional($user)->id);
            }
            //05.12.2023
            //Check param check_in then check user join event
            if ($request->get('check_in') && $user) {
                $check = $this->eventUserTicket
                    ->whereUserId($user->id)
                    ->whereTaskId($id)
                    ->exists();

                if (!$check) {
                    $statusCreateCheckin = $this->eventUserTicket->create([
                        'name' => $user->name,
                        'phone' => $user->phone ? $user->phone : '0367158269',
                        'email' => $user->email,
                        'task_id' => $id,
                        'user_id' => $user->id,
                        'is_checkin' => true,
                        'hash_code' => Str::random(35)
                    ]);
                    $show_message = 1;
                } else {
                    //Update is_checkin
                    $statusCreateCheckin = $this->eventUserTicket
                        ->whereUserId($user->id)
                        ->whereTaskId($id)
                        ->update([
                            'is_checkin' => true,
                        ]);

                    $show_message = 1;
                }
                //Send NFT to user
                $has_checkin = 1;
                return redirect()->route('web.events.show', [
                    'id' => $id,
                    'sucess_checkin' => $has_checkin
                ]);
            }

            //Case not login, redirect register. 06.12.2023
            if ($request->get('check_in') && $user == null) {
                //Set session checkin and event id
                session()->put('checkin_event', [
                    'id' => $id,
                    'type' => 'checkin'
                ]);

                return redirect()->route('web.formLoginGuest', [
                    'id' => $id,
                    'sucess_checkin' => 1
                ]);
            }
            // lay session
            $travelSessions = [];
            $session = $this->eventModel->whereTaskId($id)->whereType(TASK_SESSION)->first();
            $countEventDetail = TaskEventDetail::where('task_event_id', $session->id)->count();

            $travelSessionIds = $this->eventDetail
                ->select('travel_game_id')
                ->distinct()
                ->whereTaskEventId($session->id)
                ->pluck('travel_game_id')
                ->toArray();
            $travelSessions = $this->travelGame
                ->whereIn('id', $travelSessionIds)
                ->orderBy('created_at', 'desc')
                ->get();


            $eventSession = $this->eventModel->whereTaskId($id)->whereType(TASK_SESSION)->first();

            $sessions = $this->eventDetail->whereTaskEventId($eventSession->id)
                //->orderBy('sort', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();


            $totalCompleted = 0;

            foreach ($sessions as $session) {
                $isDoneTask = $this->checkDoneJob($session->id);
                if ($isDoneTask) {
                    $totalCompleted++;
                }
            }

            //lay booth
            $travelBoots = [];
            $booth = $this->eventModel->whereTaskId($id)->whereType(TASK_BOOTH)->first();
            $countEventDetailBooth = TaskEventDetail::where('task_event_id', $booth->id)->count();
            $travelBootsIds = $this->eventDetail
                ->select('travel_game_id')
                ->distinct()
                ->whereTaskEventId($booth->id)
                ->pluck('travel_game_id')
                ->toArray();

            $travelBooths = $this->travelGame->whereIn('id', $travelBootsIds)->get();

            $eventBooths = $this->eventModel->whereTaskId($id)->whereType(TASK_BOOTH)->first();

            $booths = $this->eventDetail->whereTaskEventId($eventBooths->id)
                //->orderBy('sort', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
//dd($travelBooths, $booths);
            $totalCompletedBooth = 0;

            foreach ($booths as $booth) {
                $isDoneTaskBooth = $this->checkDoneJob($booth->id);
                if ($isDoneTaskBooth) {
                    $totalCompletedBooth++;
                }
            }

            // check claim nft
            $check = false;
            $nft = NFTMint::where([
                'task_id' => $id,
                'status' => 1,
                'type' => 1,
            ])->first();

            if (\auth()->user()) {
                $check = UserNft::with('nftMint')
                    ->where([
                        'user_id' => \auth()->user()->id,
                        'task_id' => $id
                    ])->first();
            }
        } catch (\Exception $e) {
//            dd($e->getMessage());
            notify()->error('Error show event');
        }

//        if ($request->check_in) {
//            $taskDetail = TaskEventDetail::where([
//                'task_event_id' => $event->id
//            ])->get();
//            dd($taskDetail);
//        }

        return view('web.events.show', [
            'event' => $event,
            'user' => $user,
            'sponsor' => $sponsor,
            'download_ticket' => $download_ticket,
            'url_download_ticket' => $url_download_ticket,
            'show_message' => $show_message,
            'travelSessions' => $travelSessions,
            'travelBooths' => $travelBooths ?? [],
            'task_id' => $id,
            'session_id' => $session->id,
            'session' => $session,
            'booth' => $booth,
            'totalCompleted' => $totalCompleted,
            'countEventDetail' => $countEventDetail,
            'booth_id' => $booth->id ?? '',
            'totalCompletedBooth' => $totalCompletedBooth,
            'countEventDetailBooth' => $countEventDetailBooth,
            'checkMint' => $check,
            'nft' => $nft ?? [],
        ]);
    }

    //apiUserList
    //Get list user check in event
    //Created 20.12.2023
    public function apiUserList(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $event = $this->task->find($id);

            $userIds = $this->eventUserTicket->select('user_id')->whereTaskId($id);

            $userIds = $userIds->pluck('user_id')->toArray();
            $userIds = array_unique($userIds);
            $eventUserTickets = $this->userService->search([
                'limit' => 100,
                'userIds' => $userIds
            ]);


            $data = [];

            foreach ($eventUserTickets as $eventUserTicket) {
                $data[] = [
                    'name' => $eventUserTicket->name,
                    'email' => $eventUserTicket->email,
                    'phone' => $eventUserTicket->phone,
                    'created_at' => $eventUserTicket->created_at,
                ];
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            notify()->error('Error show event');
        }

        return response()->json([
            'status' => true,
            'message' => 'Get list user check in event successfully',
            'data' => $data
        ]);
    }

    // Get ticket
    public function orderTicket(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $name = $request->input('first') . ' ' . $request->input('last');
            $taskId = $request->input('task_id');
            $email = $request->input('email');
            $phone = $request->input('phone');
            //Check phone is empty
            if (empty($phone)) {
                $phone = '0367158269';
            }

            if (!$user) {
                $user = $this->user->whereEmail($email)->first();
                if (!$user) {
                    $user = $this->user->create([
                        'name' => $name,
                        'email' => $request->input('email'),
                        'password' => '123456a@',
                        'phone' => $phone,
                        'role' => GUEST_ROLE,
                        'email_verified_at' => now(),
                        'confirm_at' => now(),
                        'status' => USER_CONFIRM
                    ]);
                }

                Auth::login($user);
            }

            $check = $this->eventUserTicket
                ->whereUserId($user->id)
                ->whereTaskId($taskId)
                ->exists();

            if (!$check) {
                $this->eventUserTicket->create([
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $request->input('email'),
                    'task_id' => $taskId,
                    'user_id' => $user->id,
                    'hash_code' => Str::random(35)
                ]);
            } else {
                $this->eventUserTicket
                    ->whereUserId($user->id)
                    ->whereTaskId($taskId)
                    ->update([
                        'name' => $name,
                        'phone' => $phone,
                        'email' => $request->input('email'),
                    ]);
            }
            //Send Email Tickit

            $userTicket = EventUserTicket::whereUserId($user->id)
                ->whereTaskId($taskId)
                ->first();

            if ($userTicket) {
                $options = array(
                    'invoice_id' => '10087866',
                    'invoice_total' => '100.07',
                    //'download_link' => route('web.events.show', $event->id),
                    'download_link' => route('web.events.show', [
                        'id' => $taskId,
                        'download_ticket' => true
                    ]),
                    'event_name' => $userTicket->task->name,
                    'event_description' => $userTicket->task->description,
                    'event_location' => $userTicket->task->address,
                    'event_date' => $userTicket->task->start_date,
                    'event_time' => $userTicket->task->start_time,
                    'start' => $userTicket->task->start_at,
                    'end' => $userTicket->task->end_at,
                );

                Mail::to($user)->send(new \App\Mail\ThankYouCheckIn($user, $options));
                //Mail::to($user->email)->send(new EmailSendTicket($userTicket, $user));
            }

            DB::commit();
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            notify()->error('Error submit ticket');
            return redirect()->route('web.home');
        }

        notify()->success('Get ticket success');

        return redirect()->route('web.events.show', [
            'id' => $taskId,
            'download_ticket' => true
        ]);
    }

    //ticketPdf
    public function ticketPdf(Request $request, $id = null)
    {
        try {
            if (!$id) {
                $id = $request->get('id');
            }
            $user = Auth::user();
            $event = $this->task->find($id);
            $userTicket = EventUserTicket::whereUserId($user->id)
                //->whereTaskId($id)
                ->first();
            if ($userTicket) {
                // retreive all records from db
                //$urlAnswers = route('quiz-name.answers', $eventId);
                $urlAnswers = route('web.events.show', ['id' => $id, 'check_in' => true]);
                //Date time register
                $dateRegister = Carbon::parse($userTicket->created_at)->format('Y-m-d H:i:s');
                $data = [
                    'event' => $event,
                    'user' => $user,
                    'userTicket' => $userTicket,
                    'dateRegister' => $dateRegister,
                    'urlAnswers' => $urlAnswers,
                ];
                // share data to view
                view()->share('employee', $data);
                $pdf = PDF::loadView('pdf.event.ticket', $data);
                // download PDF file with download method
                return $pdf->download('Ticket_Download.pdf');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            notify()->error('Error submit ticket');
            return redirect()->route('web.home');
        }

        return redirect()->route('web.home');
    }


    public function events(Request $request)
    {
        try {

            $eventsPendings = [];

            $limit = $request->get('limit') ?? 100;

            $events = $this->taskService->search([
                'limit' => $limit,
                'type' => 1,
                'status' => 1
            ]);

            $user = Auth::user();

            //lấy thông tin user tham gia sự kiện
            if (!empty($user)) {

                // Sử dụng mối quan hệ để lấy thông tin
                $eventsPendings = Task::where(['tasks.status' => 1])
                    ->join('user_events', 'tasks.id', '=', 'user_events.task_id')
                    ->whereNotNull('tasks.name')
                    ->whereNotNull('tasks.description')
                    ->where(['user_events.user_id' => $user['id']])
                    ->orderBy('tasks.id', 'DESC')
                    ->paginate(10);

            }

        } catch (\Exception $e) {
            Log::error('Errors: ' . $e->getMessage());
        }

        $data = [
            'events' => $events,
            'eventsPending' => $eventsPendings
        ];

        return view('web.events.index', $data);
    }

    // User work job session, booth
    // method: GET
    // url: http://event.plats.test/event-job/fOtjr347cL9nHwWTox1J
    public function jobEvent(Request $request, $id)
    {
        try {

            $sessionDatas = [];
            $boothDatas = [];
            $user = Auth::user();
            $task = $this->task->whereCode($id)->first();
            $sessionNFT = session()->get('nft-' . $user->id);

            if (!$task) {
                $this->redirectPath();
            }

            $eventSession = $this->eventModel->whereTaskId($task->id)->whereType(TASK_SESSION)->first();
            $eventBooth = $this->eventModel->whereTaskId($task->id)->whereType(TASK_BOOTH)->first();
            $sessions = $this->eventDetail->whereTaskEventId($eventSession->id)->orderBy('sort', 'asc')->get();
            $booths = $this->eventDetail->whereTaskEventId($eventBooth->id)->orderBy('sort', 'asc')->get();

            return redirect(route('job.getTravelGame', ['task_id' => $task->id]));


            foreach ($sessions as $session) {
                $travel = $this->travelGame->find($session->travel_game_id);
                $job = $this->taskDone
                    ->whereUserId($user->id)
                    ->whereTaskEventDetailId($session->id)
                    ->first();

                $sessionDatas[] = [
                    'id' => $session->id,
                    'travel_game_id' => $session->travel_game_id,
                    'travel_game_name' => $travel->name,
                    'user_id' => $request->user()->id,
                    'name' => $session->name,
                    'desc' => $session->description,
                    'date' => $job ? Carbon::parse($job->created_at)->format('Y-m-d') : '',
                    'time' => $job ? Carbon::parse($job->created_at)->format('H:i') : '',
                    'required' => $session->is_required,
                    'flag' => $this->checkDoneJob($session->id),
                ];
            }

            foreach ($booths as $booth) {
                $travel = $this->travelGame->find($booth->travel_game_id);
                $job = $this->taskDone
                    ->whereUserId($user->id)
                    ->whereTaskEventDetailId($booth->id)
                    ->first();

                $boothDatas[] = [
                    'id' => $booth->id,
                    'travel_game_id' => $booth->travel_game_id,
                    'travel_game_name' => $travel->name,
                    'user_id' => $request->user()->id,
                    'name' => $booth->name,
                    'desc' => $booth->description,
                    'date' => $job ? Carbon::parse($job->created_at)->format('Y-m-d') : '',
                    'time' => $job ? Carbon::parse($job->created_at)->format('H:i') : '',
                    'required' => $booth->is_required,
                    'flag' => $this->checkDoneJob($booth->id),
                ];
            }

            $groupSessions = [];
            $groupBooths = [];
            foreach ($sessionDatas as $item) {
                $groupSessions[$item['travel_game_id']][] = $item;
            }

            foreach ($boothDatas as $item) {
                $groupBooths[$item['travel_game_id']][] = $item;
            }
        } catch (\Exception $e) {
            notify()->error('Sự kiện không tồn tại.');
            return redirect()->route('web.home');
        }

        return view('web.events.job', [
            'groupBooths' => $groupBooths,
            'groupSessions' => array_reverse($groupSessions),
            'task_id' => $task->id,
            'id' => $id,
            'url' => $sessionNFT && $sessionNFT['url'] ? $sessionNFT['url'] : null,
            'nft' => $sessionNFT && $sessionNFT['nft'] ? 1 : 0
        ]);
    }

    private function redirectPath()
    {
        notify()->error('Sự kiện không tồn tại.');

        return redirect()->route('web.home');
    }

    private function checkDoneJob($eventDetailId)
    {
        $userId = Auth::user() !== null ? Auth::user()->id : null;
        if (empty($userId)) {
            return null;
        }
        return $this->taskDone
            ->whereUserId($userId)
            ->whereTaskEventDetailId($eventDetailId)
            ->exists();

        /* $status = $this->taskDone
             ->whereUserId($userId)
             ->whereTaskEventDetailId($eventDetailId)
             ->count();
         //dd($status);
         return $status;*/
    }

    public function callbackLogin(Request $request)
    {
        if ($request->provider) {
            $user = Magic::user()->get_metadata_by_token($request->magic_credential);
            $userData = $user->content ? $user->content->data : [];
            if ($userData) {
                $existingUser = User::where('email', $userData->email)->first();
                if ($existingUser) {
                    Auth::login($existingUser);
                } else {
                    $newUser = new User();
                    $newUser->name = $userData->email;
                    $newUser->email = $userData->email;
                    $newUser->wallet_address = $userData->public_address;
                    $newUser->email_verified_at = now();
                    $newUser->status = USER_ACTIVE;
                    if ($request->provider === 'facebook') {
                        $newUser->facebook = $userData->issuer;
                    } else if ($request->provider === 'google') {
                        $newUser->google = $userData->issuer;
                    }
                    $newUser->save();

                    Auth::login($newUser);
                }

            }

        }

        return redirect()->to('/');
    }

}
