<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Resources\GroupResource;
use App\Http\Requests\JoinGroupRequest;
use App\Models\{
    Group as GroupModel,
    UserGroup
};

class Group extends ApiController
{
    /**
     * @param $taskService
     */
    public function __construct(
        private GroupModel $group,
        private UserGroup $userGroup
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit') ?? PAGE_SIZE;
            $userId = $request->user()->id;
            $groupIds = $this->userGroup->whereUserId($userId)->pluck('group_id')->toArray();

            $groups = $this->group
                ->whereStatus(true)
                ->whereNotIn('id', $groupIds)
                ->orderBy('created_at', 'desc')
                ->paginate($limit);

            $datas = GroupResource::collection($groups);
            $pages = [
                'current_page' => (int)$request->get('page'),
                'last_page' => $groups->lastPage(),
                'per_page'  => (int)$limit,
                'total' => $groups->lastPage()
            ];
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        return $this->respondWithIndex($datas, $pages);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        try {
            $group = $this->group->findOrFail($id);
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        return $this->respondWithResource(new GroupResource($group));
    }

    public function myGroups(Request $request)
    {
        try {
            $limit = $request->get('limit') ?? PAGE_SIZE;
            $userId = $request->user()->id;
            $groups = $this->group->whereHas('user_groups', function($q) use ($userId) {
                return $q->whereUserId($userId);
            })
            ->whereStatus(true)
            ->paginate($limit);

            $datas = GroupResource::collection($groups);
            $pages = [
                'current_page' => (int)$request->get('page'),
                'last_page' => $groups->lastPage(),
                'per_page'  => (int)$limit,
                'total' => $groups->lastPage()
            ];
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        return $this->respondWithIndex($datas, $pages);
    }

    public function joinGroup(JoinGroupRequest $request)
    {
        try {
            $id = $request->input('group_id');
            $userId = $request->user()->id;
            $this->group->findOrFail($id);
            $group = $this->userGroup->whereUserId($userId)->whereGroupId($id)->first();

            if ($group) {
                $group->delete();
                $mess = 'Leave group success.';
            } else {
                $data = ['user_id' => $userId, 'group_id' => $id];
                $this->userGroup->create($data);
                $mess = 'Join group success.';
            }
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        return $this->responseMessage($mess);
    }
}
