<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\SocialResource;
use App\Http\Requests\SocialRequest;
use Illuminate\Http\Request;
use App\Models\Task as ModelTask;
use App\Models\{TaskUser, UserTaskReward};
use App\Repositories\{
    TaskRepository,
    TaskSocialRepository,
    TaskUserRepository
};
use App\Services\SocialService;
use App\Helpers\ActionHelper;
use Illuminate\Support\Facades\{Http};
use Illuminate\Support\Str;
use Log;

class Social extends ApiController
{
    /**
     * @param $taskService
     * @param $modelTask
     * @param $taskUser
     * @param $taskRepository
     * @param $taskUserRepository
     * @param $taskSocialRepository
     *
     */
    public function __construct(
        private SocialService $socialService,
        private ModelTask $modelTask,
        private TaskUser $taskUser,
        private TaskRepository $taskRepository,
        private TaskSocialRepository $taskSocialRepository,
        private TaskUserRepository $taskUserRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        try {
            $limit = $request->get('limit') ?? PAGE_SIZE;
            $socials = $this->modelTask->load(['participants' => function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }])
                ->whereStatus(ACTIVE_TASK)
                ->whereType(TYPE_SOCIAL)
                ->orderBy('order', 'desc')
                ->paginate($limit);

            if (count($socials) <= 0) {
                return $this->respondNotFound();
            }
        } catch (QueryException $e) {
            return $this->respondNotFound();
        }

        $datas = SocialResource::collection($socials);
        $pages = [
            'current_page' => (int)$request->get('page'),
            'last_page' => $socials->lastPage(),
            'per_page'  => (int)$limit,
            'total' => $socials->lastPage()
        ];

        return $this->respondWithIndex($datas, $pages, 'List socials!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SocialRequest $request, $id, $socialId)
    {
        try {
            $user = $request->user();
            $tweetId = optional($this->getSocialAccount($user->token))->twitter;

            $type = ActionHelper::getTypeTwitter($request->type);
            \Illuminate\Support\Facades\Log::info('Call api: ', [
                'id' => $id,
                'type' => $type
            ]);

            if (is_null($tweetId) || $tweetId == '') {
                return $this->respondError('Account twitter not connect!', 422);
            }

            $userSocial = $this->taskSocialRepository->find($socialId);
            $userTask = $this->taskUser->whereUserId($user->id)
                ->whereTaskId($userSocial->task_id)
                ->whereSocialId($userSocial->id)
                ->first();

            if ($userTask && $userTask->status == USER_COMPLETED_TASK) {
                $label = ActionHelper::getTypeStr($userSocial->type)[1];
                return $this->responseMessage($label . ' done!');
            }

            // Service
            $isSocial = $this->socialService->performTwitter($user, $tweetId, $type, $id, $userSocial);

            if ($isSocial[0]) {
                // $dataReward = $this->socialService->saveDetailReward($user, $userSocial);
                return response()->json([
                    'message' => $isSocial[1],
                    'data' => [
                        'amount' => optional($dataReward)->amount,
                        'units' => 'PSP',
                        'message' => $isSocial[1]
                    ]
                ], 200);
            }
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respondError($isSocial[1], 422);
    }

    /**
     * Start social tasks
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function start($id, Request $request)
    {
        try {
            $userId = $request->user()->id;
            $task = $this->taskRepository->find($id);

            if ($this->taskUserRepository->countUserTaskSocial($userId, $task->id) > 0) {
                return $this->responseMessage('Social task started!');
            }

            $this->socialService->startTaskSocial($userId, $task);
        } catch(\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->responseMessage('Social task started!');
    }


    private function getSocialAccount($token)
    {
        try {
            $res = Http::withToken($token)
                ->get(config('app.api_user_url') . '/api/account-socials');

            $data = json_decode($res->getBody()->getContents())->data;
        } catch (\Exception $e) {
            $data = null;
        }

        return $data;
    }
}
