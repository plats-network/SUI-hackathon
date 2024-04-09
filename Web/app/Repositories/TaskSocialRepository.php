<?php

namespace App\Repositories;

use App\Models\TaskSocial;
use App\Repositories\Concerns\BaseRepository;


class TaskSocialRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TaskSocial::class;
    }
}
