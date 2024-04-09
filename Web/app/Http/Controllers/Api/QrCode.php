<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\QrCode\EventRequest;
use App\Http\Resources\QrCodeResource;
use App\Services\{CodeHashService, TaskService};

// Model
use App\Models\Event\{
    UserJoinEvent,
    TaskEventDetail,
    TaskEvent,
    UserEvent,
    EventUserTicket
};
use App\Models\{Task, TravelGame};
use DB;

class QrCode extends ApiController
{
    public function __construct(
        private UserJoinEvent $userJoinEvent,
        private TaskEventDetail $taskEventDetail,
        private TaskEvent $taskEvent,
        private UserEvent $userEvent,
        private EventUserTicket $eventUserTicket,
        private CodeHashService $codeHashService,
        private Task $task,
        private TravelGame $travelGame,
        private TaskService $taskService,
    ) {}

    public function qrEvent(EventRequest $request)
    {
        $data = [];
        $dataStatusess = [];

        try {
            $userId = $request->user()->id;
            $code = $request->input('code');
            $type = $request->input('type');

            if ($type == 'event') {
                $eventDetail = $this->taskEventDetail->whereCode($code)->first();
                $taskEvent = $this->taskEvent->find($eventDetail->task_event_id);

                $userJoinEvent = $this->userJoinEvent
                    ->whereUserId($userId)
                    ->whereTaskEventDetailId($eventDetail->id)
                    ->exists();

                if (!$eventDetail) {
                    return $this->respondError('Job no found!', 404);
                }

                $checkUserEvent = $this->userEvent
                    ->whereUserId($userId)
                    ->whereTaskId($taskEvent->task_id)
                    ->exists();
                if (!$checkUserEvent) {
                    $this->userEvent->create([
                        'user_id' => $userId,
                        'task_id' => $taskEvent->task_id
                    ]);
                }

                if (!$userJoinEvent) {
                    $data = [
                        'user_id' => $userId,
                        'task_event_detail_id' => $eventDetail->id,
                        'task_id' => $taskEvent->task_id,
                        'task_event_id' => $taskEvent->id,
                        'travel_game_id' => $eventDetail->travel_game_id,
                        'type' => $taskEvent->type,
                        'is_important' => $taskEvent->type == 0 ? $eventDetail->is_question : $eventDetail->is_required
                    ];

                    $this->userJoinEvent->create($data);
                }


                /// Gen code
                $taskId = $taskEvent->task_id;
                $event = $this->task->find($taskId);
                $session = $this->taskEvent->whereTaskId($taskId)->whereType(TASK_SESSION)->first();
                $booth = $this->taskEvent->whereTaskId($taskId)->whereType(TASK_BOOTH)->first();
                $travelSessionIds = $this->taskEventDetail->select('travel_game_id')->distinct()->whereTaskEventId($session->id)->pluck('travel_game_id')->toArray();
                $travelBootsIds = $this->taskEventDetail->select('travel_game_id')->distinct()->whereTaskEventId($booth->id)->pluck('travel_game_id')->toArray();
                $userId = $request->user()->id;
                $this->taskService->genCodeByUser($userId, $taskId, $travelSessionIds, $travelBootsIds, $session->id, $booth->id);
                ////End Gen code

                $data = [
                    'task_id' => $taskEvent->task_id,
                    'message' => 'success'
                ];
            } elseif ($type == 'checkin') {
                $ticket = $this->eventUserTicket->whereHashCode($code)->first();

                if (!$ticket) {
                    return $this->respondError('Ticket not found!', 404);
                }

                if ($ticket->is_checkin == true) {
                    $data = [
                        'task_id' => $ticket->task_id,
                        'message' => 'Ticket checkined!'
                    ];
                } else {
                    $ticket->update(['is_checkin' => true]);
                    $data = [
                        'task_id' => $ticket->task_id,
                        'message' => 'Checkin done!'
                    ];
                }
            }
        } catch (\Exception $e) {
            return $this->respondError("QR không đúng, vui lòng kiểm tra lại!", 500);
        }

        return $this->respondWithData($data, 'Done');
    }
}
