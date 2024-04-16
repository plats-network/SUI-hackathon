<?php

namespace App\Repositories;

use App\Models\TaskReward;
use App\Repositories\Concerns\BaseRepository;

class TaskRewardsRepository extends BaseRepository
{
    public function model()
    {
        return TaskReward::class;
    }
}
