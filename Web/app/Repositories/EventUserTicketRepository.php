<?php

namespace App\Repositories;

use App\Models\Event\EventUserTicket;
use App\Repositories\Concerns\BaseRepository;

class EventUserTicketRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EventUserTicket::class;
    }
}
