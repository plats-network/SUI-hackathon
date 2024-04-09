<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\GroupRequest;
use App\Http\Resources\Admin\GroupResource;
use App\Models\Group as GroupModel;
use App\Services\Admin\GroupService;

class Group extends ApiController
{
    /**
    * @param $groupModel
    */
    public function __construct(
        private GroupModel $groupModel,
        private GroupService $groupService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit') ?? PAGE_SIZE;

            $groups = $this->groupModel
                ->orderBy('created_at', 'desc')
                ->orderBy('status', 'desc')
                ->paginate($limit);

            $datas = GroupResource::collection($groups);
            $pages = [
                'current_page' => (int)$request->get('page'),
                'last_page' => $groups->lastPage(),
                'per_page' => (int)$limit,
                'total' => $groups->total()
            ];
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respondWithIndex($datas, $pages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        try {
            $this->groupService->store($request);
            $mess = empty($request->input('id')) ? 'Create group done!' : 'Update group done!';
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->responseMessage($mess);
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
            $group = $this->groupModel->findOrFail($id);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->respondWithResource(new GroupResource($group));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try {
            $group = $this->groupModel->findOrFail($id);
            $status = $group->status == 1 ? false : true;
            $group->update(['status' => $status]);
            $mess = $group->status == 1 ? 'Lock group seccess!' : 'Unlock group success!';
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }

        return $this->responseMessage($mess);
    }
}
