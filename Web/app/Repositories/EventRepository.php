<?php

namespace App\Repositories;

use App\Models\Event\TaskEvent;
use App\Repositories\Concerns\BaseRepository;

class EventRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TaskEvent::class;
    }
}
