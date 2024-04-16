<?php

namespace App\Services;

use App\Repositories\LocationHistoryRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TaskUserRepository;
use App\Services\Concerns\BaseService;

class UserTaskService extends BaseService
{
    /**
     * @var \App\Repositories\TaskRepository
     */
    protected $taskRepository;

    /**
     * @var \App\Repositories\LocationHistoryRepository
     */
    protected $localHistoryRepo;

    /**
     * @param \App\Repositories\TaskUserRepository $repository
     * @param \App\Repositories\TaskRepository $taskRepository
     * @param \App\Repositories\LocationHistoryRepository $localHistoryRepo
     */
    public function __construct(
        TaskUserRepository $repository,
        TaskRepository $taskRepository,
        LocationHistoryRepository $localHistoryRepo
    ) {
        $this->repository       = $repository;
        $this->taskRepository   = $taskRepository;
        $this->localHistoryRepo = $localHistoryRepo;
    }

    /**
     * @param array $conditions
     *
     * @return mixed
     */
    public function search($conditions = [])
    {
        $this->withLoad = ['task'];

        $this->makeBuilder($conditions);

        return $this->endFilter();
    }
}
