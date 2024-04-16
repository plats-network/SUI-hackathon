<?php

namespace App\Services;

use App\Repositories\TaskLocationRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Http\Request;

class TaskLocationService extends BaseService
{
    /**
     * @param \App\Repositories\TaskLocationRepository $repository
     */
    public function __construct(TaskLocationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $conditions
     *
     * @return mixed
     */
    public function search($conditions = [])
    {
        $this->makeBuilder($conditions);

        return $this->endFilter();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $taskId
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(Request $request, $taskId)
    {
        $data = $request->only([
            'name',
            'address',
            'long',
            'lat',
            'sort',
            'phone_number',
            'open_time',
            'close_time',
        ]);

        $data['task_id'] = $taskId;
        $data['status']  = ACTIVE_LOCATION_TASK;

        return $this->repository->create($data);
    }
}
