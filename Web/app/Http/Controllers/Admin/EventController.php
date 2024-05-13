<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Services\UserService;
use App\Models\Event\{
    EventDiscords,
    EventSocial,
    TaskEvent,
    TaskEventDetail,
    TaskEventReward,
    EventUserTicket,
    UserCode,
    UserJoinEvent,
    UserEvent
};
use App\Models\Quiz\Quiz;
use Illuminate\Support\Facades\Storage;
use App\Models\Game\{MiniGame};
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\{NFT\NFT,
    NFT\NFTMint,
    Task,
    TaskGallery,
    TaskGroup,
    TaskGenerateLinks,
    TravelGame,
    Sponsor,
    SponsorDetail,
    TicketCollection,
    Url,
    UserSponsor,
    User};
use App\Models\NFT\TicketNftMint;
use App\Services\Admin\{EventService, TaskService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct(
        private TaskEvent $eventModel,
        private EventService $eventService,
        private TaskService $taskService,
        private UserService $userService,
        private Task $task,
        private User $user,
        private Sponsor $sponsor,
        private SponsorDetail $sponsorDetail,
        private UserSponsor $userSponsor,
        private TravelGame $travelGame,
        private TaskEventDetail $taskEventDetail,
        private EventUserTicket $eventUserTicket,
        private TaskGenerateLinks $eventShare,
        private MiniGame $miniGame,
        private UserCode $userCode,
    ) {
        // code
    }
    public function dashboard(Request $request){

        $limit = $request->get('limit') ?? 10;
        $clientUser = Auth::user();
        $condition = [
            'limit' => $limit,
            'type' => EVENT,
        ];
        if (Auth::user()->role != ADMIN_ROLE) {
            $condition['creator_id'] = $clientUser->id;
        }
        $events = $this->taskService->search($condition);
        return view('cws.dashboard.index', ['events'=>$events]);
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {

        $limit = $request->get('limit') ?? PAGE_SIZE;
        $clientUser = Auth::user();
        $condition = [
            'limit' => $limit,
            'type' => EVENT,
        ];
        if (Auth::user()->role != ADMIN_ROLE) {
            $condition['creator_id'] = $clientUser->id;
        }
        $events = $this->taskService->search($condition);

        foreach ($events as $event) {
            if ($event->code == null || $event->code == '') {
                $event->update(['code' => genCodeTask()]);
            }
        }

        $tab = $request->get('tab') ?? 0;
        $data = [
            'events' => $events,
            'tab' => $tab,
        ];

        return view('cws.event.index', $data);
    }

    public function upEventDetail(Request $request)
    {
        try {
            $id = $request->input('id');
            $detail = $this->taskEventDetail->find($id);
            if ($detail) {
                $detail->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('desc'),
                    'nft_link' => $request->input('nft')
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    public function upEventVip(Request $request, $id)
    {
        try {
            $detail = $this->taskEventDetail->find($id);
            if ($detail) {
                $detail->update(['is_required' => $detail->is_required ? false : true]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    public function upEventSort(Request $request, $id)
    {
        try {
            $detail = $this->taskEventDetail->find($id);

            if ($detail) {
                $detail->update(['sort' => $request->input('sort')]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    // method: GET
    // url: http://cws.plats.test/overview/{id}
    public function overview(Request $request, $id)
    {
        try {
            $event = $this->taskService->findEvent($id);
            $session = $this->eventModel
                ->whereType(TASK_SESSION)
                ->whereTaskId($event->id)
                ->first();
            $booth = $this->eventModel
                ->whereType(TASK_BOOTH)
                ->whereTaskId($event->id)
                ->first();

            if ($session && empty($session->code)) {
                $session->update(['code' => Str::random(35)]);
            }

            if ($booth && empty($booth->code)) {
                $booth->update(['code' => Str::random(35)]);
            }

            $shares = $this->eventShare
                ->whereTaskId($event->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error');
            abort(404);
        }

        return view('cws.event.overview', [
            'event' => $event,
            'session' => $session,
            'booth' => $booth,
            'shares' => $shares,
        ]);
    }

    // method: GET
    // url: http://cws.plats.test/minigame/{id}
    public function miniGame(Request $request, $id)
    {
        try {
            $session = $this->eventModel
                ->whereType(TASK_SESSION)
                ->whereTaskId($id)
                ->first();
            $booth = $this->eventModel
                ->whereType(TASK_BOOTH)
                ->whereTaskId($id)
                ->first();

            $travelSessionIds = $this->taskEventDetail
                ->select('travel_game_id')
                ->distinct()
                ->where('task_event_id', $session->id)
                ->pluck('travel_game_id')
                ->toArray();

            $travelBoothIds = $this->taskEventDetail
                ->select('travel_game_id')
                ->distinct()
                ->where('task_event_id', $booth->id)
                ->pluck('travel_game_id')
                ->toArray();

            foreach ($travelSessionIds as $travelId) {
                $checkExists = $this->miniGame
                    ->where('task_event_id', $session->id)
                    ->where('travel_game_id', $travelId)->exists();
                if (!$checkExists) {
                    $this->miniGame->create([
                        'task_event_id' => $session->id,
                        'travel_game_id' => $travelId,
                        'code' => Str::random(100),
                        'type' => 0,
                        'num' => 1
                    ]);
                }
            }

            foreach ($travelBoothIds as $tBoothid) {
                $checkExists = $this->miniGame
                    ->where('task_event_id', $booth->id)
                    ->where('travel_game_id', $tBoothid)->exists();
                if (!$checkExists) {
                    $this->miniGame->create([
                        'task_event_id' => $booth->id,
                        'travel_game_id' => $tBoothid,
                        'code' => Str::random(100),
                        'type' => 1,
                        'num' => 1
                    ]);
                }
            }

            $miniGameSessions = $this->miniGame->where('task_event_id', $session->id)->orderBy('id', 'asc')->get();
            $miniGameBooths = $this->miniGame->where('task_event_id', $booth->id)->orderBy('id', 'asc')->get();
        } catch (\Exception $e) {
            abort(404);
        }

        return view('cws.event.minigame', [
            'miniGameSessions' => $miniGameSessions,
            'miniGameBooths' => $miniGameBooths,
            'event_id' => $id,
        ]);
    }

    public function setupMiniGame(Request $request)
    {
        try {
            $id = $request->input('id');
            $miniGame = $this->miniGame->find($id);

            if (!$miniGame) {
                return $this->resError();
            } else {
                $num = $request->input('num');
                $is_game = $request->input('is_game');
                $type_prize = $request->input('type_prize');

                $miniGame->update([
                    'banner_url' => $request->input('banner_url'),
                    'type_prize' => (int)$type_prize,
                    'num' => $num,
                    'is_game' => (int)$is_game == 0 ? 1 : $is_game
                ]);
            }
        } catch (\Exception $e) {
            return $this->resError();
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    public function sponsor(Request $request, $id)
    {
        try {
            $sponsor = $this->sponsor
                ->with(['sponsorDetails', 'userSponsors', 'task'])
                ->whereTaskId($id)
                ->first();
            if (!$sponsor) {
                abort(404);
            }

            $userSponsors = $this->userSponsor
                ->with('user', 'sponsor', 'task', 'sponsorDetail')
                ->where('sponsor_id', $sponsor->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            abort(404);
        }

        return view('cws.event.sponsor', [
            'sponsor' => $sponsor,
            'userSponsors' => $userSponsors
        ]);
    }

    public function genLink(Request $request)
    {
        try {
            $id = $request->input('id');
            $name = $request->input('name');
            $taskId = $request->input('task_id');
            if ($id) {
                $share = $this->eventShare->find($id);
                $share->update(['name' => $name]);

                notify()->success('Update link successfully');
            } else {
                $this->eventShare->create([
                    'task_id' => $taskId,
                    'name' => $name,
                    'type' => 0,
                    'url' => Str::random(10),
                ]);
                notify()->success('Create link successfully');
            }
        } catch (\Exception $e) {
            notify()->error('Error: ' . $e->getMessage());

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'OK'
        ], 200);
    }

    /*
     * template
     *
     */
    public function template(Request $request, $id = '')
    {
        $index = $request->get('index');
        $type = $request->get('type', 1);
        $getInc = $request->get('inc', 1);
        if (empty($id)) {
            $id = $request->get('flag_check');
        }

        $dataView['type'] = $type;
        $dataView['indexImageItem'] = $id;
        $dataView['getInc'] = $getInc + 1;
        $dataView['travelGames'] = $this->travelGame->whereStatus(true)->get();

        if ($type == 1) {
            return response()->json([
                'code' => 200,
                'status' => 200,
                'html' => view('cws.event._template.item_session', $dataView)->render(),
                'message' => 'success',
            ], 200);
        }
        if ($type == 2) {
            return response()->json([
                'code' => 200,
                'status' => 200,
                'html' => view('cws.event._template.item_booth', $dataView)->render(),
                'message' => 'success',
            ], 200);
        }

        //quizz
        if ($type == 3) {
            return response()->json([
                'code' => 200,
                'status' => 200,
                'html' => view('cws.event._template.item_quiz', $dataView)->render(),
                'message' => 'success',
            ], 200);
        }

        if ($type == 4) {
            return response()->json([
                'code' => 200,
                'status' => 200,
                'html' => view('cws.event._template.item_sponsor', $dataView)->render(),
                'message' => 'success',
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /** @var Task $task */
        $task = new TaskEvent();
        $userId = Auth::user()->id;
        $isCopyModel = $request->get('copy', null);
        if ($isCopyModel) {
            $post_id = $request->get('id', 0);
            $modelClone = Task::with('taskSocials', 'taskLocations', 'taskEventSocials', 'taskGenerateLinks', 'taskEventDiscords')->find($post_id);
            //Clone data
            if ($modelClone) {
                $task = $modelClone->replicate();
            }
        }
        $id = $task->id;
        $eventId = $id;
        $taskGroup = TaskGroup::where('task_id', $id)->pluck('group_id');
        $taskGallery = TaskGallery::where('task_id', $id)->pluck('url_image');
        $booths = TaskEvent::where('task_id', $id)->with('detail')->where('type', 1)->first();
        $sessions = TaskEvent::where('task_id', $id)->with('detail')->where('type', 0)->first();
        $sponsor = $this->sponsor->whereTaskId($id)->first();

        $taskEventDiscords = $task->taskEventDiscords;

        if ($taskEventDiscords == null) {
            $taskEventDiscords = new EventDiscords();
        }
        $taskEventSocials = $task->taskEventSocials;
        if ($taskEventSocials == null) {
            $taskEventSocials = new EventSocial();
        }
        if ($sessions == null) {
            $sessions = new TaskEvent();
            $sessions->type = 0;
        }
        if ($booths == null) {
            $booths = new TaskEvent();
            $booths->type = 1;
        }

        if (!$sponsor) {
            $sponsor = $this->sponsor;
        }

        $quiz = [];
        $image = [];
        $task['group_tasks'] = $taskGroup;
        $task['task_galleries'] = $image;
        $task['booths'] = $booths;
        $task['sessions'] = $sessions;
        $task['quiz'] = $quiz;
        $task['sponsor'] = $sponsor;

        $activeTab = $request->get('tab') ?? '0';
        //Is preview
        $isPreview = $request->get('preview') ?? '0';
        $travelGames = $this->travelGame->whereUserId($userId)->whereStatus(true)->get();

        //New Code
        //05.12.2023 - Url Check In event
        $userCheckIn = $this->listUsers($id); //List user check in event

        //$urlAnswers = route('quiz-name.answers', $eventId);
        $urlAnswers = route('web.events.show', ['id' => $eventId ?? 1, 'check_in' => true]);
        $qrCode = QrCode::format('png')->size(250)->generate($urlAnswers);
        $nftItem = new NFT();
        $allNetwork = NFT::getAllNetworkName();

        $data = [
            'nftItem' => $nftItem,
            'allNetwork' => $allNetwork,
            'urlAnswers' => $urlAnswers,
            'event' => $task,
            'sessions' => $sessions,
            'booths' => $booths,
            'quiz' => $quiz,
            'eventId' => $eventId,
            'userCheckIn' => $userCheckIn,
            'qrCode' => base64_encode($qrCode),
            'sponsor' => $sponsor,
            'taskEventSocials' => $taskEventSocials,
            'taskEventDiscords' => $taskEventDiscords,
            'group_tasks' => $taskGroup,
            'task_galleries' => $image,
            'total_file' => count($image),
            'activeTab' => $activeTab,
            'is_update' => 0,
            'isPreview' => $isPreview,
            'travelGames' => $travelGames,
            'nft_hash_id'=>md5(uniqid())// id hash để quản lý bên mạng web3
        ];
        return view('cws.event.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $inputAll = $request->all();
            $inputAll['type'] = 1;
            $request->validate(['name' => 'required']);

            //$company->fill($request->post())->save();
            $this->eventService->store($request);
            notify()->success('Create event successfully');
        } catch (\Exception $e) {
            notify()->error('Create event fail!' . $e->getMessage());
            Log::error('Create event cws: ' . $e->getMessage());
            return redirect()->route('cws.eventCreate');
        }

        return redirect()->route('cws.eventList');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Event $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Request $request, $id)
    {
        $eventId = $id;

        $userId = Auth::user()->id;
        if (Str::contains($request->path(), 'event-preview')) {
            if (!$request->get('preview') == 1) {
                notify()->error('Không thể truy cập');
                return redirect()->route('cws.eventList');
            }
        }

        /** @var Task $task */
        $task = Task::with('taskSocials', 'taskLocations', 'taskEventSocials', 'taskGenerateLinks', 'taskEventDiscords')->find($id);
//        $task = Task::with('taskEvents.detail')->find($id);
//        dd($task->taskEvents(), $task->taskEvents);
        $session = $task->taskEvents()->firstWhere('type', 0);
        //TODO: dung 1
        //các bảng này không có dữ liệu
        $taskGroup = TaskGroup::where('task_id', $id)->pluck('group_id');
        $taskGallery = TaskGallery::where('task_id', $id)->pluck('url_image');
        //end 1

        $booths = TaskEvent::where('task_id', $id)
            ->with('detail')->where('type', 1)
            ->first();

        if ($booths) {
            foreach ($booths['detail'] as $booth) {
                $booth['totalUserJob']  = totalUserJob($booth['id']);
            }
        }

        $sessions = TaskEvent::where('task_id', $id)->with('detail')
            ->where('type', 0)
            ->first();
        
        if ($sessions) {

            foreach ($sessions['detail'] as $session) {
                $session['totalUserJob']  = totalUserJob($session['id']);
            }
        }
        
        $sponsor = $this->sponsor->whereTaskId($id)->first();

        $taskEventDiscords = $task->taskEventDiscords;

        if ($taskEventDiscords == null) {
            $taskEventDiscords = new EventDiscords();
            $taskEventDiscords->task_id = $id;
            $taskEventDiscords->save();
        }

        $taskEventSocials = $task->taskEventSocials;

        if ($taskEventSocials == null) {
            $taskEventSocials = new EventSocial();
            $taskEventSocials->task_id = $id;
            $taskEventSocials->save();
        }

        if ($sessions == null) {
            $sessions = new TaskEvent();
            $sessions->task_id = $id;
            $sessions->type = 0;
            $sessions->save();
        }

        if ($booths == null) {
            $booths = new TaskEvent();
            $booths->task_id = $id;
            $booths->type = 1;
            $booths->save();
        }

        if (!$sponsor) {
            $sponsor = new Sponsor();
            $sponsor->task_id = $id;
            $sponsor->save();
        }

        $quiz = Quiz::where('task_id', $id)->with('detail')->get();

        foreach ($quiz as $value) {
            foreach ($value['detail'] as $key => $value) {
                $value['key'] = $key;
            }
        }
        $image = [];
        foreach ($taskGallery as $item) {
            $image[]['url'] = $item;
        }


        $task['group_tasks'] = $taskGroup ?? [];
        $task['task_galleries'] = $image;
        $task['booths'] = $booths;
        $task['sessions'] = $sessions;
        $task['quiz'] = $quiz;
        $task['sponsor'] = $sponsor;

        $activeTab = $request->get('tab') ?? '0';
        //Is preview
        $isPreview = $request->get('preview') ?? '0';
        $travelGames = $this->travelGame->whereUserId($userId)->whereStatus(true)->get();

        //New Code
        //05.12.2023 - Url Check In event
        $userCheckIn = $this->listUsers($id); //List user check in event

        //$urlAnswers = route('quiz-name.answers', $eventId);
//        $urlAnswersFull = route('web.events.show', ['id' => $eventId, 'check_in' => true]);
        $urlAnswersFull = 'https://' .config('plats.event').'/event/'.$eventId.'?check_in=1';
        //Shorten url
//        $urlAnswers = Url::shortenUrl($urlAnswersFull);
        $urlAnswers = $urlAnswersFull;
//        dd($urlAnswers);

        $qrCode = QrCode::format('png')->size(250)->generate($urlAnswers);

        $allNetwork = NFT::getAllNetworkName();

        //Load Item NFT
        $nftItem = NFT::query()
            ->where('task_id', $id)
            ->first();

        //Check empty create one
        if (!$nftItem) {
            $nftItems = new NFT();
            $nftItems->task_id = $id;
            $nftItems->name = 'NFT Item';
            $nftItems->description = 'NFT Item';
            $nftItems->image_url = 'https://cws.plats.com.vn/images/logo.png';

            $nftItems->save();
        }
        //Get first $sessions id
        $select_session_id = TaskEventDetail::query()
            ->whereTaskEventId($sessions->id)
            ->orderBy('created_at', 'asc')
            ->value('id');

        //tổng user tham gia sự kiện
        $countUserJoinEvent = UserJoinEvent::where('task_id', $id)->count();

        //tổng user đăng kí sự kiện
        $countUserRegisterEvent = EventUserTicket::where('task_id', $id)->count();

        //user vào sự kiện
        $dataUserJoinEvent = UserJoinEvent::select('user_id', 'updated_at', 'type')->get();

        $countNFT = NFTMint::where([
            'task_id' => $task->id,
            'type' => 1,
//            'nft_symbol' => 'Standard'
        ])->pluck('nft_symbol');

        $countNFTBooth = NFTMint::where([
            'task_id' => $task->id,
            'type' => 3
        ])->get();

        $countNFTSession = NFTMint::where([
            'task_id' => $task->id,
            'type' => 2
        ])->get();
        
        $ticketCollection = TicketCollection::where('task_id', $task->id)->orderBy('ticket_collection.id','ASC')->get();
        
        $ticketNftMint = TicketNftMint::join('ticket_collection','ticket_collection.id','=','ticket_nft_mint.ticket_id')
            ->where('ticket_collection.task_id', $task->id)
            ->orderBy('ticket_nft_mint.ticket_id','ASC')
            ->get();
        
        $data = [
            'eventId' => $eventId,
            'allNetwork' => $allNetwork,
            'urlAnswers' => $urlAnswers,
            'nftItem' => $nftItem,
            'event' => $task,
            'sessions' => $sessions,
            'booths' => $booths,
            'quiz' => $quiz,
            'qrCode' => base64_encode($qrCode),
            'userCheckIn' => $userCheckIn,
            'select_session_id' =>  $select_session_id,
            'sponsor' => $sponsor,
            'taskEventSocials' => $taskEventSocials,
            'taskEventDiscords' => $taskEventDiscords,
            'group_tasks' => $taskGroup,
            'task_galleries' => $image,
            'total_file' => count($image),
            'activeTab' => $activeTab,
            'is_update' => 1,
            'isPreview' => $isPreview,
            'travelGames' => $travelGames,
            'countUser' => [
                'userJoinEvent' => $countUserJoinEvent,
                'userRegisterEvent' => $countUserRegisterEvent
            ],
            'countNFT' => $countNFT,
            'countNFTBooth' => $countNFTBooth,
            'countNFTSession' => $countNFTSession,
            'ticketCollection'=>$ticketCollection,
            'ticketNftMint'=>$ticketNftMint,
        ];
        
        return view('cws.event.edit-custom', $data);
    }

    private function listUsers($id)
    {
        $users = [];
        try {
            $userIds = $this->eventUserTicket->select('user_id')->whereTaskId($id)->get();

            $userIds = $userIds->pluck('user_id')->toArray();

            $userIds = array_unique($userIds);
            $users = $this->userService->search([
                'limit' => 100,
                'userIds' => $userIds
            ]);
        } catch (\Exception $e) {
        }

        return $users;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $company)
    {
        try {
            $input = $request->all();

            //dd($input);

            $request->validate(['name' => 'required']);
            $this->eventService->store($request);
            notify()->success('Update successful!');
        } catch (\Exception $e) {
            notify()->error('Update fail!');
            Log::error('Update Event Cws: ' . $e->getMessage());
            return redirect()->route('cws.eventList', ['tab' => 0]);
        }

        return redirect()->route('cws.eventList', ['tab' => 0]);
    }

    //preview
    public function preview(Request $request, $id)
    {
        //Preview Event
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Event $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try {
            $event = Task::findOrFail($id);
            $event->update(['status' => 99]);
            notify()->success('Delete event successfully!');
        } catch (\Exception $e) {
            notify()->error('Delete event fail');
            Log::error('Error delete event cws: ' . $e->getMessage());
            return redirect()->route('cws.eventList');
        }

        return redirect()->route('cws.eventList');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $task = $this->task->find($id);
            if ($task) {
                $status = $task->status == true ? false : true;
                $task->update(['status' => $status]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 200);
        }

        return response()->json([
            'message' => 'ok'
        ], 200);
    }

    // Update status job
    public function updateJob(Request $request, $id)
    {
        try {
            $taskEventId = $request->get('event_id');
            $detail = $this->taskEventDetail
                ->whereTaskEventId($taskEventId)
                ->whereCode($id)
                ->latest()
                ->first();

            if ($detail) {
                $status = $detail->status == true ? false : true;
                $detail->update(['status' => $status]);
            } else {
                return response()->json([
                    'message' => 'Not Found!'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'OK'
        ], 200);
    }

    public function genCode(Request $request, $id)
    {
        try {
            $type = $request->get('type');
            $userId = $request->get('user_id');
            $info = $this->eventUserTicket
                ->where('task_id', $id)
                ->where('user_id', $userId)
                ->first();

            if ($info) {
                $color = '#' . substr(md5(rand()), 0, 6);
                if ($type == 'session') {
                    $max = $this->eventUserTicket->max('sesion_code') + 1;
                    $info->update([
                        'sesion_code' => $max,
                        'color_session' => $color
                    ]);
                } elseif ($type == 'booth') {
                    $max = $this->eventUserTicket->max('booth_code') + 1;
                    $info->update([
                        'booth_code' => $max,
                        'color_boot' => $color
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'OK'
        ], 200);
    }

    public function getPrizeList(Request $request, $id)
    {
        try {
            $miniGame = $this->miniGame->find($id);
            $eventTask = $this->eventModel->find($miniGame->task_event_id);

            $prizeLists = $this->userCode
                ->with(['user', 'taskEvent', 'travelGame'])
                ->where('task_event_id', $miniGame->task_event_id)
                ->where('travel_game_id', $miniGame->travel_game_id)
                ->where('type', $miniGame->type)
                ->where('is_prize', true)
                ->get();
        } catch (\Exception $e) {
            abort(404);
        }

        return view('cws.event.prizeList', [
            'prizeLists' => $prizeLists,
            'id' => $eventTask->task_id
        ]);
    }

    public function listUserCodes(Request $request, $id, $travelId)
    {
        try {
            $eventTask = $this->eventModel->find($id);
            $userLists = [];
            $userIds = $this->userCode
                ->select('user_id')
                ->distinct()
                ->where('travel_game_id', $travelId)
                ->where('task_event_id', $id)
                ->pluck('user_id')
                ->toArray();

            $users = $this->user->whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                $codes = $this->userCode->whereTaskEventId($id)
                    ->whereTravelGameId($travelId)
                    ->whereUserId($user->id)
                    ->pluck('number_code')->implode(',');
                $userLists[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'code' => $codes
                ];
            }
        } catch (\Exception $e) {
            abort(404);
        }

        return view('cws.event.userList', [
            'userLists' => $userLists,
            'id' => $eventTask->task_id
        ]);
    }

    // Api
    public function updMiniGame(Request $request, $id)
    {
        try {
            if (Auth::guest()) {
                return $this->resError();
            } else {
                $miniGame = $this->miniGame->find($id);

                if (!$miniGame) {
                    return $this->resError();
                } else {
                    $miniGame->update(['status' => $miniGame->status ? false : true]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Errors'
            ], 500);
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    public function setupVip(Request $request, $id)
    {
        try {
            if (Auth::guest()) {
                return $this->resError();
            } else {
                $miniGame = $this->miniGame->find($id);
                if (!$miniGame) {
                    return $this->resError();
                } else {
                    $miniGame->update(['is_vip' => $miniGame->is_vip ? false : true]);
                }
            }
        } catch (\Exception $e) {
            return $this->resError();
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }


    public function setTicketVip(Request $request, $id)
    {
        try {
            if (Auth::guest()) {
                return $this->resError();
            } else {
                $ticket = $this->eventUserTicket->find($id);
                if (!$ticket) {
                    return $this->resError();
                } else {
                    $ticket->update(['is_vip' => $ticket->is_vip ? false : true]);
                }
            }
        } catch (\Exception $e) {
            return $this->resError();
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    private function resError(Request $request)
    {
        return response()->json([
            'message' => 'Errors'
        ], 400);
    }
}
