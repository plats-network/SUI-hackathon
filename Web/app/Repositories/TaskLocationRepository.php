<?php

namespace App\Repositories;

use App\Models\TaskLocation;
use App\Repositories\Concerns\BaseRepository;

class TaskLocationRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TaskLocation::class;
    }
}
