<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event\{TaskEvent, EventUserTicket, TaskEventDetail, UserCode};
use App\Models\Game\{MiniGame};
use Log;

class HomeController extends Controller
{
    public function __construct(
        private TaskEvent       $taskEvent,
        private EventUserTicket $eventUserTicket,
        private UserCode        $userCode,
        private MiniGame        $miniGame,
    )
    {
        // code
    }


    public function dayOne(Request $request)
    {
        try {
            $isClear = false;
            $isTest = env('IS_TEST', true);
            if ($isTest) {
                for ($i = 1; $i <= 1000; $i++) {
                    $codes[] = $i;
                }
            } else {

                $update = $request->input('update');
                if ($update == 2) {
                    $isClear = true;
                }
                //For production
                $task_id = '9ae602bb-5fe5-4f35-85a9-c6021fc22930';
                //For local
                //$task_id = '9ae26d36-4825-4136-944c-0adf7b748b7d';
                $codes = [];
                $eventSession = TaskEvent::query()->whereTaskId($task_id)->whereType(TASK_SESSION)->first();
                $eventBooth = TaskEvent::query()->whereTaskId($task_id)->whereType(TASK_BOOTH)->first();
                $sessions = TaskEventDetail::query()->whereTaskEventId($eventSession->id)->orderBy('sort', 'asc')->get();
                foreach ($sessions as $session) {

                }

                if ($isClear) {
                    $codes = UserCode::query()
                        ->where('task_event_id', $session->id)
                        //->where('travel_game_id', '9a13167f-4a75-4a46-aa5b-4fb8baea4b9b')
                        //->whereIn('user_id', $userIds)
                        ->whereType(0)
                        //Filter by created_at >= 2023-12-22 00:00:00
                        //->where('created_at', '>=', '2023-12-22 00:00:00')
                        ->forceDelete();
                }

                $limit = 1000;
                $codes = UserCode::query()
                    ->where('task_event_id', $session->id)
                    //->where('travel_game_id', '9a13167f-4a75-4a46-aa5b-4fb8baea4b9b')
                    //->whereIn('user_id', $userIds)
                    ->whereType(0)
                    //Filter by created_at >= 2023-12-22 00:00:00
                    //->where('created_at', '>=', '2023-12-22 00:00:00')
                    ->inRandomOrder()
                    ->pluck('number_code')
                    ->toArray();
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error datas',
                'data' => null
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successful',
            'data' => $codes
        ], 200);
    }

    public function dayThree(Request $request, $task_id)
    {
        try {
            $codes = [];
            $eventSession = TaskEvent::query()->whereTaskId($task_id)->whereType(TASK_SESSION)->first();
            $eventBooth = TaskEvent::query()->whereTaskId($task_id)->whereType(TASK_BOOTH)->first();
            $sessions = TaskEventDetail::query()->whereTaskEventId($eventSession->id)->orderBy('sort', 'asc')->get();
            foreach ($sessions as $session) {

            }

            $limit = 1000;
            $codes = UserCode::query()
                ->where('task_event_id', $session->id)
                //->where('travel_game_id', '9a13167f-4a75-4a46-aa5b-4fb8baea4b9b')
                //->whereIn('user_id', $userIds)
                ->whereType(0)
                ->inRandomOrder()
                ->pluck('number_code')
                ->toArray();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error datas',
                'data' => null
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successful',
            'data' => $codes
        ], 200);
    }

    public function dayOne2(Request $request)
    {
        try {
            $codes = [];
            if (env('APP_ENV') != 'production') {
                for ($i = 1; $i <= 30000; $i++) {
                    $codes[] = $i;
                }
            } else {
                $userIds = $this->eventUserTicket
                    ->whereTaskId('9a131bf1-d41a-4412-a075-599e97bf6dcb')
                    ->whereIsVip(true)
                    ->pluck('user_id')
                    ->toArray();

                $limit = 1000;
                $codes = $this->userCode
                    ->where('task_event_id', '9a131bf1-d4fa-4368-bb6b-c4e5f8d1da08')
                    ->where('travel_game_id', '9a13167f-4a75-4a46-aa5b-4fb8baea4b9b')
                    ->whereIn('user_id', $userIds)
                    ->whereType(0)
                    ->inRandomOrder()
                    ->pluck('number_code')
                    ->toArray();
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error datas',
                'data' => null
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successful',
            'data' => $codes
        ], 200);
    }

    public function dayTwo(Request $request)
    {
        try {
            $codes = [];

            if (env('APP_ENV') != 'production') {
                for ($i = 1; $i <= 30000; $i++) {
                    $codes[] = $i;
                }
            } else {
                $userIds = $this->eventUserTicket
                    ->whereTaskId('9a131bf1-d41a-4412-a075-599e97bf6dcb')
                    ->whereIsVip(true)
                    ->pluck('user_id')
                    ->toArray();
                $codes = $this->userCode
                    ->where('task_event_id', '9a131bf1-d4fa-4368-bb6b-c4e5f8d1da08')
                    ->where('travel_game_id', '9a1316c2-56b7-4e70-816a-5a56c110ccac')
                    ->whereIn('user_id', $userIds)
                    ->whereType(0)
                    ->inRandomOrder()
                    ->pluck('number_code')
                    ->toArray();
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error datas',
                'data' => null
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successful',
            'data' => $codes
        ], 200);
    }

    public function boothCodes(Request $request)
    {
        try {
            $codes = [];

            if (env('APP_ENV') != 'production') {
                for ($i = 1; $i <= 30000; $i++) {
                    $codes[] = $i;
                }
            } else {
                $codes = $this->userCode
                    ->where('task_event_id', '9a131bf1-db68-4f38-9232-8c6cb2039681')
                    ->where('travel_game_id', '9a131755-0cc0-4849-8391-256df36f36f4')
                    // ->whereIn('user_id', $userIds)
                    ->whereType(1)
                    ->inRandomOrder()
                    ->pluck('number_code')
                    ->toArray();
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error datas',
                'data' => null
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successful',
            'data' => $codes
        ], 200);
    }

    //https://cws.plats.test/game/
    //d7zXNbFLsGwqwlABr9z2ukftu4W0t3hb1crLi8nZBck8PvYvUJlJtkgUiWBBFOqxFgVcfw5IAaw4mEAkbiOiocjkMq1VHY79OWIQ
    public function miniGame(Request $request, $code)
    {

        try {
            $miniGame = $this->miniGame->whereCode($code)->first();

            if (!$miniGame) {
                abort(404);
            }

            $codes = $this->userCode
                ->where('task_event_id', $miniGame->task_event_id)
                ->where('travel_game_id', $miniGame->travel_game_id)
                ->whereType($miniGame->type)
                ->whereIsPrize(false);

            if ($miniGame->is_vip) {
                $event = $this->taskEvent->find($miniGame->task_event_id);
                $userIds = $this->eventUserTicket
                    ->whereTaskId($event->task_id)
                    ->whereIsVip(true)
                    ->pluck('user_id')
                    ->toArray();
                $codes = $codes->whereIn('user_id', $userIds);
            }

            $codes = $codes->inRandomOrder()->pluck('number_code')->toArray();

        } catch (\Exception $e) {
            abort(404);
        }

        return view('game.game', [
            'numbers' => $codes,
            'code' => $code,
            'img' => $miniGame->banner_url ?? 'bg_game_4.png',
            'num' => $miniGame->num,
            'is_locked' => $miniGame->status,
            'event_id' => $miniGame->task_event_id,
            'travel_id' => $miniGame->travel_game_id,
        ]);
    }

    public function updateResult(Request $request)
    {
        try {
            $code = $request->input('code');
            $codeIds = $request->input('ids');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }

        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    private function resError(Request $request)
    {
        return response()->json([
            'message' => 'Error'
        ], 500);
    }

    private function resOk(Request $request)
    {
        return response()->json([
            'message' => 'Ok'
        ], 200);
    }

    public function gameSession(Request $request, $code)
    {
        try {
            $miniGame = $this->miniGame->whereCode($code)->first();

            if (!$miniGame) {
                abort(404);
            }
            $numbers = [];
            for ($i = 0; $i <= 1000; $i++) {
                $numbers[] = $i + 1;
            }
        } catch (\Exception $e) {
            abort(404);
        }

        return view('game.demo', [
            'numbers' => $numbers,
            'num' => $miniGame->num,
            'type_name' => $miniGame->is_game,
            'img' => $miniGame->banner_url ?? 'bg_game_4.png',
        ]);
    }

    public function gameBooth(Request $request, $code)
    {
        try {
            $taskEvent = $this->taskEvent
                ->whereCode($code)
                ->firstOrFail();

            $events = $this->eventUserTicket
                ->whereTaskId($taskEvent->task_id)
                ->whereNotNull('booth_code')
                ->whereIsBooth(false)
                ->inRandomOrder()
                ->get();
        } catch (\Exception $e) {
            abort(404);
        }

        return view('game.booth', [
            'events' => $events,
            'task_id' => $taskEvent->task_id
        ]);
    }

    public function updateSession($task_id, $num, Request $request)
    {
        try {
            $event = $this->eventUserTicket
                ->whereTaskId($task_id)
                ->whereNotNull('sesion_code')
                ->whereIsSession(false)
                ->whereSesionCode($num)
                ->first();
            if ($event) {
                $event->update(['is_session' => true]);
            }
        } catch (\Exception $e) {

        }

        return response()->json([
            'status' => 'ok',
        ], 200);
    }

    public function updateBooth($task_id, $num, Request $request)
    {
        try {
            $event = $this->eventUserTicket
                ->whereNotNull('booth_code')
                ->whereTaskId($task_id)
                ->whereIsBooth(false)
                ->whereBoothCode($num)
                ->first();
            if ($event) {
                $event->update(['is_booth' => true]);
            }
        } catch (\Exception $e) {

        }

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
