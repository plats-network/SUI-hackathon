<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repositories\TaskUserRepository;
use App\Http\Resources\NoticeResource;
use App\Http\Resources\UserTaskRewardResource;
use App\Models\{Task, UserTaskReward};
use Illuminate\Http\Request;

class TaskNotice extends ApiController
{
    /**
     * @param App\Repositories\TaskUserRepository $taskUserRepository
     */
    public function __construct(
        private TaskUserRepository $taskUserRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->taskUserRepository
                ->getTaskStatus(USER_PROCESSING_TASK, true)
                ->get();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound('Data not found!');
        }

        return $this->respondWithResource(NoticeResource::collection($data));
    }

    // API test notices
    /**
     * Display a listing of the resource.
     * TEST
     * @return \Illuminate\Http\Response
     */
    public function getTask(Request $request) {
        $type = $request->type;

        if ($type == 'new_task') {
            $task = Task::all()->random(1)->first();
            $tile = 'Có task mới';
        } elseif ($type == 'box') {
            $task = UserTaskReward::whereUserId($request->user_id)
                ->where('is_tray', true)
                ->first();
            $tile = 'Bạn Có box';
        } else {
            $task = Task::all()->random(1)->first();
            $tile = 'Task sắp hết hạn';
        }

        $data = null;
        if ($task) {
            $data = [
                'id' => $type == 'box' ? $task->detail_reward_id : $task->id,
                'title' => $tile,
                'desc' => 'Nội dung mô tả'
            ];
        }

        return response()->json([
            'data' => $data
        ], 200);
    }
}
