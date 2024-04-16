<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\TaskRequest;
use App\Http\Resources\Admin\RewardResource;
use App\Http\Resources\Admin\TaskResource;
use App\Models\Event\TaskEvent;
use App\Models\Quiz\Quiz;
use App\Models\Task;
use App\Models\TaskGallery;
use App\Models\TaskGroup;
use App\Models\TaskLocation;
use App\Models\TaskLocationJob;
use App\Models\TaskSocial;
use App\Services\Admin\TaskService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Tasks extends ApiController
{
    public function __construct(
        private TaskService $taskService

    )
    {
//        $this->middleware('client_admin');
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? PAGE_SIZE;
        if (Auth::user()->role == CLIENT_ROLE) {
            $rewards = $this->taskService->search(['limit' => $limit, 'creator_id' => Auth::user()->id,'type' => 0]);
        } else {
            $rewards = $this->taskService->search(['limit' => $limit,'type' => 0]);
        }
        $datas = TaskResource::collection($rewards);
        $pages = [
            'current_page' => (int)$request->get('page'),
            'last_page' => $rewards->lastPage(),
            'per_page' => (int)$limit,
            'total' => $rewards->total()
        ];

        return $this->respondWithIndex($datas, $pages);
    }

    public function store(TaskRequest $request)
    {

        if ($request->filled('id')) {
//            $checkStatusTask = Task::where('status', TASK_PUBLIC)->where('id', $request->input('id'))->first();
//            if ($checkStatusTask) {
//                return $this->respondError('Can’t edit a published task', 422);
//            }
        }
        $reward = $this->taskService->store($request);

        return $this->responseMessage('success');
    }

    public function edit($id)
    {
        $task = Task::with( 'taskSocials', 'taskLocations','taskEventSocials','taskGenerateLinks','taskEventDiscords')->find($id);
        $taskGroup = TaskGroup::where('task_id',$id)->pluck('group_id');
        $taskGallery = TaskGallery::where('task_id',$id)->pluck('url_image');
        $booths = TaskEvent::where('task_id',$id)->with('detail')->where('type',1)->first();
        $sessions = TaskEvent::where('task_id',$id)->with('detail')->where('type',0)->first();
        $quiz = Quiz::where('task_id',$id)->with('detail')->get();
        foreach ($quiz as  $value){
            foreach ($value['detail'] as $key => $value){
                $value['key'] = $key;
            }
        }
        $image = [];
        foreach ($taskGallery as $item){
            $image[]['url'] = $item;
        }
        $task['group_tasks'] = $taskGroup;
        $task['task_galleries'] = $image;
        $task['booths'] = $booths;
        $task['sessions'] = $sessions;
        $task['quiz'] = $quiz;
        return $this->responseMessage($task);
    }

    public function destroy($id)
    {
        $checkStatusTask = Task::where('status', TASK_PUBLIC)->where('id', $id)->first();
        if ($checkStatusTask) {
            return $this->respondError('Can’t delete a published task', 422);
        }
        $getIdLocatios = TaskLocation::where('task_id', $id)->pluck('id');
        TaskLocationJob::whereIn('task_location_id', $getIdLocatios)->delete();
        Task::where('status', TASK_DRAFT)->where('id', $id)->delete();
        TaskGroup::where('task_id', $id)->delete();
        TaskGallery::where('task_id', $id)->delete();
        TaskSocial::where('task_id', $id)->delete();
        TaskLocation::where('task_id', $id)->delete();
        return $this->responseMessage('success');
    }
}
