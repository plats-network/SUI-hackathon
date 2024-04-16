<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event\EventUserTicket;
use App\Models\Event\TaskEvent;
use App\Models\Event\TaskEventDetail;
use App\Models\Event\UserJoinEvent;
use App\Services\Admin\EventUserTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Event extends Controller
{
    public function __construct(
        private EventUserTicketService $eventUserTicketService,
        private TaskEventDetail        $taskEventDetail,
        private TaskEvent              $taskEvent,
        private UserJoinEvent          $userJoinEvent,
        private EventUserTicket        $eventUserTicket
    )
    {
        $this->middleware('client_admin');
    }

    public function index(Request $request)
    {
        return view(
            'cws.event.index'
        );
    }

    public function userEvent($id)
    {
        return view(
            'admin.event.user_event', ['task_id' => $id]
        );
    }

    public function edit($id)
    {
        return view(
            'admin.event.edit', ['task_id' => $id]
        );
    }

    public function create()
    {
        return view(
            'admin.event.create'
        );
    }

    public function preview($id)
    {
        return view(
            'admin.event.preview', ['task_id' => $id]
        );
    }

    public function apiUserEvent(Request $request, $id)
    {
        $limit = $request->get('limit') ?? PAGE_SIZE;
        $rawData = $this->eventUserTicketService->search(['limit' => $limit, 'task_id' => $id]);
        //update user_id
        $session = $this->taskEvent->whereTaskId($id)->whereType(0)->first();
        $sessionDetail = $this->taskEventDetail->whereTaskEventId($session->id)->count();
        $booth = $this->taskEvent->whereTaskId($id)->whereType(1)->first();
        $bootDetail = $this->taskEventDetail->whereTaskEventId($booth->id)->count();
        foreach ($rawData as &$item) {
            if ($item->user_id == null) {
                $checkUser = \App\Models\User::where('email', $item->email)->first();
                if ($checkUser) {
                    EventUserTicket::where('email', $item->email)->where('task_id', $item->task_id)->update(['user_id' => $checkUser->id, 'type' => 0]);
                }
            }

        }
        $rawDataNew = $this->eventUserTicketService->search(['limit' => $limit, 'task_id' => $id]);
        foreach ($rawDataNew as &$item) {
            {
                if ($item->user_id != null) {
                    $eventUserTicket = $this->eventUserTicket->whereTaskId($id)->whereUserId($item->user_id)->first();
                    if ($booth) {
                        $countMaxBooth = $this->userJoinEvent->whereTaskEventId($booth->id)->whereUserId($item->user_id)->count();
                        if ($eventUserTicket) {
                            $compareBooth = $countMaxBooth . '/' . $bootDetail;
                            $item['compare_booth'] = $compareBooth;
                        }
                    }
                    if ($session) {
                        $countMaxSession = $this->userJoinEvent->whereTaskEventId($session->id)->whereUserId($item->user_id)->count();
                        if ($eventUserTicket) {
                            $compareSession = $countMaxSession . '/' . $sessionDetail;
                            $item['compare_session'] = $compareSession;
                        }
                    }
                }
            }

        }
        return response()->json($rawDataNew);
    }
}
