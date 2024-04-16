<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\QrCode\EventRequest;
use App\Http\Resources\QrCodeResource;
// Model
use App\Models\{Task, TravelGame};
use App\Models\Event\{UserEvent, TaskEvent, TaskEventDetail, UserJoinEvent, UserCode};
use App\Http\Resources\{
    TaskResource
};
use Carbon\Carbon;

class Event extends ApiController
{
    public function __construct(
        private Task $task,
        private TravelGame $travelGame,
        private UserEvent $userEvent,
        private TaskEvent $taskEvent,
        private TaskEventDetail $eventDetail,
        private UserJoinEvent $taskDone,
        private UserCode $userCode,
    ) {}

    public function index(Request $request)
    {
        $datas = [];
        $userId = $request->user()->id;

        $userEvents = $this->userEvent
            ->with(['user', 'task'])
            ->whereUserId($userId)
            ->whereStatus(0)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        foreach($userEvents as $item) {
            $datas[] = [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'event_id' => $item->task_id,
                'name' => optional($item->task)->name,
                'image_path' => optional($item->task)->banner_url,
                'address' => optional($item->task)->address,
            ];
        }

        return response()->json([
            'message' => 'List event improgress',
            'status' => 'success',
            'data' => $datas
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $task = $this->task->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Not found!',
                'status' => 'success',
                'data' => null
            ], 200);
        }

        $eventSession = $this->taskEvent->whereTaskId($task->id)->whereType(TASK_SESSION)->first();
        $eventBooth = $this->taskEvent->whereTaskId($task->id)->whereType(TASK_BOOTH)->first();

        $sessions = $this->eventDetail->whereTaskEventId($eventSession->id)->get();
        $booths = $this->eventDetail->whereTaskEventId($eventBooth->id)->get();

        $travelBoothIds = $this->eventDetail
            ->select('travel_game_id')
            ->distinct()
            ->whereTaskEventId($eventSession->id)
            ->pluck('travel_game_id')
            ->toArray();
        $travelSessionIds = $this->eventDetail
            ->select('travel_game_id')
            ->distinct()
            ->whereTaskEventId($eventSession->id)
            ->pluck('travel_game_id')
            ->toArray();

        $dataSessions = [];
        $dataBooths = [];

        foreach($sessions as $item) {
            $dataSessions[] = [
                'id' => $item->id,
                'name' => $item->name,
                'desc' => $item->description,
                'flag' => $this->checkDoneJob($item->id, $user->id),
            ];
        }

        foreach($booths as $item) {
            $dataBooths[] = [
                'id' => $item->id,
                'name' => $item->name,
                'desc' => $item->description,
                'flag' => $this->checkDoneJob($item->id, $user->id),
            ];
        }

        /// Travel Games
        $travelSessions = [];
        $travelBooths = [];
        $sTravels = $this->travelGame->whereIn('id', $travelSessionIds)->get();
        $bTravels = $this->travelGame->whereIn('id', $travelBoothIds)->get();

        foreach($sTravels as $item) {
            $sCodes = $this->userCode
                ->whereUserId($user->id)
                ->whereTaskEventId($eventSession->id)
                ->whereTravelGameId($item->id)
                ->whereType(0)
                ->pluck('number_code')
                ->toArray();

            $travelSessions[] = [
                'id' => $item->id,
                'name' => $item->name,
                'note' => $item->note,
                'prize_at' => Carbon::parse($item->prize_at)->format('Y-m-d H:i'),
                'codes' => $sCodes ? $sCodes : null,
            ]; 
        }

        foreach($bTravels as $item) {
            $bCodes = $this->userCode
                ->whereUserId($user->id)
                ->whereTaskEventId($eventBooth->id)
                ->whereTravelGameId($item->id)
                ->whereType(1)
                ->pluck('number_code')
                ->toArray();

            $travelBooths[] = [
                'id' => $item->id,
                'name' => $item->name,
                'note' => $item->note,
                'prize_at' => Carbon::parse($item->prize_at)->format('Y-m-d H:i'),
                'codes' => $bCodes ? $bCodes : null,
            ]; 
        }

        // Response
        $data = [
            'id' => $task->id,
            'name' => $task->name,
            'desc' => $task->description,
            'image' => $task->banner_url,
            'sessions' => $dataSessions,
            'booths' => $dataBooths,
            'session_game' => $travelSessions,
            'booth_game' => $travelBooths,
        ];

        return response()->json([
            'message' => 'Job detail',
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    private function checkDoneJob($eventDetailId, $userId)
    {
        return $this->taskDone
            ->whereUserId($userId)
            ->whereTaskEventDetailId($eventDetailId)
            ->exists();
    }
}
