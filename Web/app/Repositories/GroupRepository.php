<?php

namespace App\Repositories;


use App\Models\Group;
use App\Repositories\Concerns\BaseRepository;
use Carbon\Carbon;

class GroupRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Group::class;
    }
}
