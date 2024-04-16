<?php

namespace App\Repositories;


use App\Models\TaskUser;
use App\Repositories\Concerns\BaseRepository;
use Carbon\Carbon;

class TaskUserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TaskUser::class;
    }

    /**
     * @param $taskId
     * @param $userId
     *
     * @return null | TaskUser
     */
    public function userStartedTask($taskId, $userId, $locaId)
    {
        return $this->model::with('task:id,name', 'taskLocations')
            ->whereHas('task', function($query) {
                $query->where('type', TYPE_CHECKIN);
            })
            ->where('task_id', $taskId)
            ->where('user_id', $userId)
            ->where('location_id', $locaId)
            ->first();
    }
    
    /**
     * Get all task doing of a user and ignore current task
     * 
     * @param $taskId
     * @param $userId
     *
     * @return null | TaskUser
     */
    public function userDoingOtherTasks($taskId, $userId)
    {
        return $this->model::with('task:id,name', 'taskLocations')
            ->where('task_id', '!=', $taskId)
            ->where('user_id', $userId)
            ->where('status', USER_PROCESSING_TASK)
            ->first();
    }
    
    /**
     * Get all task doing of a user
     * 
     * @param $taskId
     * @param $userId
     *
     * @return null | TaskUser
     */
    public function userDoingTask($userId)
    {
        return $this->model::with('task:id,name,type', 'taskLocations')
            ->whereHas('task', function($query) {
                $query->where('type', TYPE_CHECKIN);
            })
            ->where('user_id', $userId)
            ->where('status', USER_PROCESSING_TASK)
            ->first();
    }

    /**
     * @param $taskId
     * @param $userId
     * @param int $status
     *
     * @return mixed
     */
    public function updateStatusTask($taskId, $userId, $status = USER_PROCESSING_TASK)
    {
        return $this->model
            ->where('task_id', $taskId)
            ->where('user_id', $userId)
            ->update(['status' => $status]);
    }

    /**
     * @return mixed
     */
    public function getTaskStatus($status = 0, $type = false)
    {
        $query = $this->model
            ->with('task')
            ->where('status', $status);

        if ($type) {
            $now = Carbon::now()->subMinutes(10);
            $query->where('time_end', '>=', $now)->where('time_end', '<=', Carbon::now());
        }

        return $query;
    }

    /**
     * @param $userId User ID
     * @param $taskId Task ID
     * @param $userSocialId User Social ID
     *
     * @return mixed
     */
    public function firstOrNewSocial($userId, $taskId, $userSocialId)
    {
        return $this->model
            ->firstOrNew([
                'user_id' => $userId,
                'task_id' => $taskId,
                'social_id' => $userSocialId
            ]);
    }

    /**
     * @param $userId User ID
     * @param $taskId Task ID
     *
     * @return mixed
     */
    public function countUserTaskSocial($userId, $taskId)
    {
        return $this->model
            ->whereUserId($userId)
            ->whereTaskId($taskId)
            ->count();
    }
}
