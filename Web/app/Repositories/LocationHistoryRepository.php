<?php

namespace App\Repositories;

use App\Models\TaskLocationHistory;
use App\Repositories\Concerns\BaseRepository;

class LocationHistoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TaskLocationHistory::class;
    }

    /**
     * @param string $userId
     * @param string $locaId
     *
     * @return mixed
     */
    public function location($userId, $locaId)
    {
        return $this->model
            ->ofUser($userId)
            ->where('location_id', $locaId)
            ->first();
    }

    /**
     * Get check-in histories of user by list location
     *
     * @param string $userId
     * @param string | array[string, string] $locas list id of location
     */
    public function myHistoryInLocas($userId, $locas)
    {
        return $this->userStatusInSubTasks($userId, $locas);
    }

    /**
     * @param $userId
     * @param $subtaskIds
     *
     * @return mixed
     */
    public function userStatusInSubTasks($userId, $subtaskIds)
    {
        $builder = $this->model->ofUser($userId);

        if (is_string($subtaskIds)) {
            return $builder->where('location_id', $subtaskIds)->get();
        }

        return $builder->whereIn('location_id', $subtaskIds)->get();
    }

    /**
     * @param $userId
     * @param $taskId
     *
     * @return mixed
     */
    public function firstOrNewHistory($userId, $taskId, $locaId)
    {
        return $this->model
            ->firstOrNew(
                ['user_id' => $userId],
                ['task_id' => $taskId],
                ['location_id' => $locaId]
            );
    }
}
