<?php

namespace App\Repositories;

use App\Models\Reward;
use App\Repositories\Concerns\BaseRepository;

class RewardRepository extends BaseRepository
{ 
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Reward::class;
    }

    /**
     * @param $taskId
     * @param $locaId
     *
     * @return mixed
     */
    // public function taskHasLocation($taskId, $locaId)
    // {
    //     return $this->model
    //         ->hasLocation($locaId)
    //         ->findOrFail($taskId);
    // }
}
