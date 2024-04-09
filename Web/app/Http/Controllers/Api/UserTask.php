<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserTaskHistoryResource;
use App\Services\UserTaskService;
use Illuminate\Http\Request;

class UserTask extends ApiController
{
    /**
     * @param App\Services\UserTaskService $userTaskService
     */
    public function __construct(
        private UserTaskService $userTaskService
    ) {}

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function histories(Request $request)
    {
        $userId = $request->user()->id;

        $datas = $this->userTaskService
            ->search(['user_id' => $userId]);

        return UserTaskHistoryResource::collection($datas);
    }
}
