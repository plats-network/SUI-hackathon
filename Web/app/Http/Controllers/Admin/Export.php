<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserJoinEvent;
use App\Http\Controllers\Controller;
use App\Services\Admin\EventUserTicketService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Controller
{
    public function __construct(
        private EventUserTicketService $eventUserTicketService
    )
    {
        $this->middleware('client_admin');
    }

    public function userJoinEvent(Request $request)
    {
        $rawData = $this->eventUserTicketService->search([
            'task_id' => $request->input('task_id'),
            'all' => true
        ]);

        return Excel::download(new UserJoinEvent($rawData), 'user-join-event.xlsx');
    }
}
