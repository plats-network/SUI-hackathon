<?php

namespace App\Services\Admin;

use App\Repositories\EventUserTicketRepository;
use App\Services\Concerns\BaseService;

class EventUserTicketService extends BaseService
{
    protected $repository;
    public function __construct(
        EventUserTicketRepository $eventUserTicketRepository,

    )
    {
        $this->repository = $eventUserTicketRepository;

    }

    public function search($conditions = [])
    {
        $this->makeBuilder($conditions);

        if ($this->filter->has('name')) {
            $this->builder->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->filter->get('name') . '%');
            });

            // Remove condition after apply query builder
            $this->cleanFilterBuilder('name');
        }

        if ($this->filter->has('task_id')) {
            $this->builder->where(function ($q) {
                $q->where('task_id',$this->filter->get('task_id'));
            });
            // Remove condition after apply query builder
            $this->cleanFilterBuilder('task_id');
        }

        if ($this->filter->has('phone')) {
            $this->builder->where(function ($q) {
                $q->where('phone', 'LIKE', '%' . $this->filter->get('phone') . '%');
            });

            // Remove condition after apply query builder
            $this->cleanFilterBuilder('phone');
        }
        if ($this->filter->has('email')) {
            $this->builder->where(function ($q) {
                $q->where('email', 'LIKE', '%' . $this->filter->get('email') . '%');
            });

            // Remove condition after apply query builder
            $this->cleanFilterBuilder('email');
        }
        return $this->endFilter();
    }
}
