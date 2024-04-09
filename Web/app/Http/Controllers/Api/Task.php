<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\{
    CheckInTaskRequest,
    StartTaskRequest,
    UserActionRequest
};
use App\Http\Requests\Task\{TaskStartRequest, JobRequest};
use App\Http\Resources\{
    TaskResource,
    TaskUserResource,
    TaskDogingResource,
    SocialResource,
    TaskDetailResource,
};
use App\Repositories\{LocationHistoryRepository, TaskRepository, TaskUserRepository};
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Models\{
    Task as ModelTask,
    UserTaskAction, TaskUser,
    TaskLocationJob, TaskSocial
};
use App\Models\Event\{EventUserTicket};
use App\Models\User\{
    UserReward, UserRewardTemp, UserTaskHistory
};

use App\Models\Task\TaskUserActionLog;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\{DB, Http};
use Carbon\Carbon;
use Log;
use Cache;

class Task extends ApiController
{
    /**
     * @param $taskService
     * @param $modelTask
     * @param $locationHistoryRepository
     * @param $taskRepository
     * @param $taskUserRepository
     *
     */
    public function __construct(
        private TaskService $taskService,
        private ModelTask $modelTask,
        private TaskUser $taskUser,
        private TaskUserActionLog $logTask,
        private TaskLocationJob $taskLocationJob,
        private TaskSocial $taskSocial,
        private TaskLocation $taskLocation,
        private LocationHistoryRepository $locationHistoryRepository,
        private TaskRepository $taskRepository,
        private TaskUserRepository $taskUserRepository,
        private UserTaskAction $userTaskAction,
        private UserReward $userReward,
        private UserRewardTemp $userRewardTemp,
        private UserTaskHistory $userTaskHistory,
        private EventUserTicket $ticket,
    ) {}

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        try {
            $type = $request->input('type');
            $keyword = $request->input('keyword');
            $userId = optional($request->user())->id;
            $limit = $request->get('limit') ?? PAGE_SIZE;
            $tasks = $this->modelTask
                ->with(['taskLocations', 'taskSocials'])
                ->whereType(EVENT)
                ->whereStatus(ACTIVE_TASK);

            if ($keyword && $keyword != '') {
                $tasks = $tasks->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('address', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            }

            if ($type && $type == 'regised') {
                $limit = 10;
                $taskIds = $this->ticket
                    ->select('task_id')
                    ->whereUserId($userId)
                    ->pluck('task_id')
                    ->toArray();
                $tasks = $tasks
                    ->whereIn('id', $taskIds)
                    ->where('end_at', '>=', Carbon::now()->subDays(30));
            }

            if ($type && $type == 'uptrend') {
                $tasks = $tasks->where('end_at', '>=', Carbon::now()->subDays(30))->inRandomOrder();
                $limit = 10;
            } elseif ($type && $type == 'upcoming') {
                $tasks = $tasks;
                $limit = 10;
            } else {
                $tasks = $tasks;
            }

            $tasks = $tasks->orderBy('created_at', 'desc')
                ->orderBy('end_at', 'asc')
                ->paginate($limit);

        } catch (QueryException $e) {
            return $this->respondNotFound();
        }

        $datas = TaskResource::collection($tasks);
        $pages = [
            'current_page' => (int)$request->get('page'),
            'last_page' => $tasks->lastPage(),
            'per_page'  => (int)$limit,
            'total' => $tasks->lastPage()
        ];

        return $this->respondWithIndex($datas, $pages);
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function detail(Request $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $task = $this->modelTask->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return $this->respondWithResource(new TaskDetailResource($task));
    }

    // Task like and pinned
    public function taskAction(UserActionRequest $request)
    {
        DB::beginTransaction();

        try {
            $type = $request->input('type');
            $taskId = $request->input('task_id');
            $userId = $request->user()->id;
            $typeNum = ($type == 'like' || $type == 'unlike') ? TASK_LIKE : TASK_PIN;
            $userLikePin = $this->userTaskAction->whereUserId($userId)->whereTaskId($taskId)->whereType($typeNum)->first();
            $mess = 'Liked';
            $params = ['user_id' => $userId, 'task_id' => $taskId, 'type' => $typeNum];

            if ($type == 'like') {
                if (!$userLikePin) { $this->userTaskAction->create($params); }
            }

            if ($type == 'unlike') {
                if ($userLikePin) { $userLikePin->delete(); }
                $mess = 'Unliked';
            }

            if ($type == 'pin') {
                if (!$userLikePin) {
                    $this->userTaskAction->create($params);
                }

                $mess = 'Pinned';
            }

            if ($type == 'unpin') {
                if ($userLikePin) { $userLikePin->delete(); }
                $mess = 'Unpinned';
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respondNotFound();
        }

        return $this->responseMessage($mess);
    }

    /**
     * User execute task with location
     *
     * @param \App\Http\Requests\StartTaskRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function startTask(TaskStartRequest $request)
    {
        DB::beginTransaction();

        try {
            $userId = $request->user()->id;
            $taskId = $request->input('task_id');
            $task = $this->modelTask->findOrFail($taskId);
            $checkStart = $this->taskUser->whereUserId($userId)->whereTaskId($taskId)->first();
            $actionParams = ['user_id' => $userId, 'task_id' => $taskId];
            $mess = 'Start done!';

            if (!$checkStart) {
                $checkStart = $this->taskUser->create(array_merge($actionParams, ['status' => 0, 'finish_at' => $task->end_at]));
            }

            if ($request->input('type') == 'start') {
                $checkStart->update(['status' => 0]);
                $this->logTask->create(array_merge($actionParams, ['status' => 0]));
            } else {
                $checkStart->update(['status' => 2]);
                $this->logTask->create(array_merge($actionParams, ['status' => 1]));
                $mess = 'Cancel done!';
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::err($e->getMessage());

            return $this->respondNotFound();
        }

        return $this->responseMessage($mess);
    }

    public function myTasks(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $limit = $request->get('limit') ?? PAGE_SIZE;

            $tasks = $this->modelTask
                ->with(['taskLocations', 'taskSocials', 'taskUsers'])
                ->whereHas('taskUsers', function($q) use ($request, $userId) {
                    $q->whereUserId($userId);
                    $type = $request->input('type');

                    if ($request->has('type') && $$type == 'doing') {
                        $q->whereStatus(USER_TASK_DOING);
                    } elseif ($request->has('type') && $$type == 'done') {
                        $q->whereStatus(USER_TASK_DONE);
                    } elseif($request->has('type') && $$type == 'cancel') {
                        $q->whereStatus(USER_TASK_CANCEL);
                    } elseif($request->has('type') && $$type == 'expired') {
                        $q->whereStatus(USER_TASK_TIMEOUT);
                    }
                })
                ->whereStatus(ACTIVE_TASK)
                ->orderBy('created_at', 'desc')
                ->orderBy('end_at', 'asc')
                ->paginate($limit)
                ;
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        $datas = TaskResource::collection($tasks);
        $pages = [
            'current_page' => (int)$request->get('page'),
            'last_page' => $tasks->lastPage(),
            'per_page'  => (int)$limit,
            'total' => $tasks->lastPage()
        ];

        return $this->respondWithIndex($datas, $pages);
    }


    // Tas
    public function startJob(JobRequest $request)
    {
        DB::beginTransaction();

        try {
            $userId = $request->user()->id;
            $type = $request->input('type');
            $taskId = $request->input('task_id');
            $jobId = $request->input('job_id');
            $this->modelTask->findOrFail($taskId);

            if ($type == 'checkin') {
                $job = $this->taskLocationJob->findOrFail($jobId);
                $rewardId = optional($job->taskLocation)->reward_id;
                $amount = $job->taskLocation->amount;
            } else {
                $job = $this->taskSocial->findOrFail($jobId);
                $rewardId = $job->reward_id;
                $amount = $job->amount;
            }

            $checkJob = $this->userTaskHistory
                ->whereUserId($userId)
                ->whereTaskId($taskId)
                ->whereSourceId($job->id)
                ->first()
                ;

            if ($checkJob) {
                return $this->responseMessage('Job started');
            }

            $checkReward = $this->userReward
                ->whereUserId($userId)
                ->whereRewardId($rewardId)
                ->first()
                ;

            if ($checkReward) {
                $userAmmount = $checkReward->amount;
                $checkReward->update([
                    'amount' => $userAmmount + $amount
                ]);
            } else {
                $this->userReward->create([
                    'user_id' => $userId,
                    'reward_id' => $rewardId,
                    'amount' => $amount
                ]);
            }

            $this->userRewardTemp->create([
                'user_id' => $userId,
                'reward_id' => $rewardId,
                'amount' => $amount,
                'status' => 0
            ]);

            $this->userTaskHistory->create([
                'user_id' => $userId,
                'task_id' => $taskId,
                'reward_id' => $rewardId,
                'source_id' => $job->id,
                'type' => $type == 'checkin' ? 0 : 1,
                'amount' => $amount,
                'status' => 0,
                'ip_address' => '127.0.0.1',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            \Log::err("Error: " . $e->getMessage());
            DB::rollBack();

            return $this->respondNotFound();
        }

        return $this->responseMessage('Job done!');
    }

    // Get Event Hot
    public function getEventTaskHots(Request $request)
    {
        $type = $request->input('type');
        $typeId = isset($type) && $type == 'event' ? 1 : 0;

        $expiresAt = Carbon::now()->endOfDay();
        $cacheEventIds = Cache::get('eventIds');
        $cacheTaskIds = Cache::get('taskIds');

        if (!$cacheEventIds && $typeId == 1) {
            $eventIds =  $this->modelTask
                ->whereType(1)
                ->inRandomOrder()
                ->limit(4)->pluck('id')->toArray();
            Cache::put('eventIds', $eventIds, $expiresAt);
            $cacheEventIds = Cache::get('eventIds');
        }

        if (!$cacheTaskIds) {
            $taskIds =  $this->modelTask
                ->whereType(0)
                ->inRandomOrder()
                ->limit(4)->pluck('id')->toArray();
            Cache::put('taskIds', $taskIds, $expiresAt);
            $cacheTaskIds = Cache::get('taskIds');
        }
        // dd($cacheTaskIds);

        if ($type == 'event') {
            $tasks = $this->modelTask->whereId($cacheEventIds)->get();
        } else {
            $tasks = $this->modelTask->whereId($cacheTaskIds)->get();
        }

        $datas = TaskResource::collection($tasks);

        return $this->respondWithResource($datas);
    }

    public function listTicks(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $tickets = [];
            $myTickets = $this->ticket
                ->whereUserId($userId)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            if (empty($myTickets)) {
                return $this->respondNotFound();
            }

            foreach($myTickets as $item) {
                $event = $this->modelTask->find($item->task_id);
                $tickets[] = [
                    'id' => $item->id,
                    'user_id' => $userId,
                    'event_id' => $item->task_id,
                    'img' => commonImg($event->banner_url),
                    'event_name' => optional($event)->name,
                    'address' => optional($event)->address,
                    'start_at' => dateFormat(optional($event)->start_at),
                    'end_at' => dateFormat(optional($event)->end_at),
                    'type' => 1,
                    'user_name' => optional($request->user())->name,
                    'type_name' => 'Basic',
                    'qr_content' => 'https://'.config('plats.cws').'/checkin?type=checkin'.'&id='.$item->hash_code
                ];
            }
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        return response()->json([
            'message' => 'List tickets',
            'code' => 200,
            'status' => 'success',
            'data' => $tickets
        ], 200);
    }

    /**
     * User cancel task
     *
     * @param \Illuminate\Http\Request $request
     * @param $taskId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, $taskId)
    {
        $userId = $request->user()->id;
        $dataTaskDoing = $this->taskService->getTaskDoing($userId);

        if (!$dataTaskDoing) {
            return $this->respondNotFound();
        }

        $cancel = $this->taskService
            ->cancel($userId, $taskId, $dataTaskDoing->location_id);

        if (is_null($cancel)) {
            return $this->respondNotFound();
        }

        return $this->responseMessage('DONE!');
    }
}
