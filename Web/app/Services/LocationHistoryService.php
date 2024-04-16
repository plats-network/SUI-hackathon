<?php

namespace App\Services;

use App\Repositories\LocationHistoryRepository;
use App\Services\Concerns\BaseService;

class LocationHistoryService extends BaseService
{
    /**
     * @param \App\Repositories\LocationHistoryRepository $repository
     */
    public function __construct(LocationHistoryRepository $repository)
    {
        $this->repository = $repository;
    }
}
