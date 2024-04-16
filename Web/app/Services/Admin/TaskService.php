<?php

namespace App\Services\Admin;

use App\Models\Task;
use App\Models\TaskGallery;
use App\Models\TaskGroup;
use App\Models\TaskLocation;
use App\Models\TaskLocationJob;
use App\Repositories\TaskLocationRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TaskRewardsRepository;
use App\Services\Concerns\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskService extends BaseService
{
    protected $repository;

    public function __construct(
        TaskRepository $taskRepository,
    )
    {
        $this->repository = $taskRepository;
    }

    public function search($conditions = [])
    {
        $user = auth()->user();

        $this->makeBuilder($conditions);

        if ($this->filter->has('status')) {
            $this->builder->where(function ($q) {
                $q->where('status', $this->filter->get('status'));
            });
            $this->cleanFilterBuilder('status');
        }
        if ($this->filter->has('type')) {
            $this->builder->where(function ($q) {
                $q->where('type', $this->filter->get('type'));
            });
            // Remove condition after apply query builder
            $this->cleanFilterBuilder('type');
        }
        if ($this->filter->has('creator_id')) {
            $this->builder->where(function ($q) {
                $q->where('creator_id', $this->filter->get('creator_id'));
            });
            // Remove condition after apply query builder
            $this->cleanFilterBuilder('creator_id');
        }

        if ($this->filter->has('start_at') || $this->filter->has('end_at')) {
            $start = $this->filter->get('start_at');
            $end = $this->filter->get('end_at');

            if ($start && empty($end)) {
                $this->builder->where(function ($q) use ($start) {
                    $q->where('created_at', '>=', $start);
                });
            } elseif (empty($start) && $end) {
                $this->builder->where(function ($q) use ($end) {
                    $q->where('created_at', '<=', $end);
                });
            } else {
                $this->builder->where(function ($q) use ($start, $end) {
                    $q->where('created_at', '>=', $start)
                        ->where('created_at', '<=', $end);
                });
            }
            $this->cleanFilterBuilder('start_at');
            $this->cleanFilterBuilder('end_at');
        }

        if ($this->filter->has('name')) {
            $this->builder->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->filter->get('name') . '%');
            });

            // Remove condition after apply query builder
            $this->cleanFilterBuilder('name');
        }
//        if ($user && $user->role  ==  CLIENT_ROLE) {
//        if ($user && in_array($user->role, [CLIENT_ROLE, ADMIN_ROLE])) {
//            $this->builder->where(function ($q) use ($user) {
//                $q->where('creator_id', $user->id);
//            });
//        }
        $this->builder->with('taskLocations', 'taskSocials', 'taskGalleries', 'taskGenerateLinks', 'taskEvents', 'userGetTickets')->orderBy('created_at', 'desc');
        return $this->endFilter();
    }

    public function store($request)
    {
        if (!$request->filled('id')) {
            return $this->create($request);
        }
        return $this->update($request, $request->input('id'));
    }

    public function update(Request $request, $id)
    {
        $data = Arr::except($request->all(), '__token');
        $baseTask = Arr::except($request->all(), '__token');
        DB::beginTransaction();
        try {
            $baseTask['creator_id'] = Auth::user()->id;
            $baseTask['status'] = true;
            /** @var Task $dataBaseTask */
            $dataBaseTask = $this->repository->update($baseTask, $id);

            TaskGallery::where('task_id', $id)->delete();
            if (isset($baseTask['task_galleries'])) {
                foreach ($baseTask['task_galleries'] as $uploadedFile) {
                    $dataBaseTask->taskGalleries()->create(['url_image' => empty($uploadedFile['url']) ? $uploadedFile : $uploadedFile['url']]);
                }
            }
            if (isset($baseTask['group_tasks'])) {
                TaskGroup::where('task_id', $id)->delete();
                foreach ($baseTask['group_tasks'] as $item) {
                    DB::table('task_groups')->insert([
                        'task_id' => $id,
                        'group_id' => $item,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

            }
            $locations = Arr::get($data, 'task_locations');
            if ($locations) {
                TaskLocation::where('task_id', $id)->delete();
                foreach ($locations as $location) {
                    $idTaskLocation = $dataBaseTask->taskLocations()->create($location);
                    if ($location['task_location_jobs']) {
                        foreach ($location['task_location_jobs'] as $item) {
                            $idTaskLocation->taskLocationJob()->create($item);
                        }
                    }
                }
            }
            $socials = Arr::get($data, 'task_socials');
            if ($socials) {
                $dataBaseTask->taskSocials()->delete();
                $dataBaseTask->taskSocials()->createMany($socials);
            }
            //Task session has one
            $taskSessions = Arr::get($data, 'sessions');
            if ($taskSessions) {


            }
            DB::commit();
        } catch (RuntimeException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new RuntimeException($exception->getMessage(), 500062, $exception->getMessage(), $exception);
        }
    }

    public function create(Request $request)
    {
        $data = Arr::except($request->all(), '__token');
        $baseTask = Arr::except($request->all(), '__token');
        DB::beginTransaction();
        try {
            if ($request->hasFile('banner_url')) {
                $uploadedFile = $request->file('banner_url');
                $path = 'task/image/banner' . Carbon::now()->format('Ymd');
                $baseTask['banner_url'] = Storage::disk('s3')->putFileAs($path, $uploadedFile, $uploadedFile->hashName());
            }
            $baseTask['creator_id'] = Auth::user()->id;
            $baseTask['status'] = true;

            $dataBaseTask = $this->repository->create($baseTask);
            if ($baseTask['task_galleries']) {
                foreach ($baseTask['task_galleries'] as $uploadedFile) {
                    $dataBaseTask->taskGalleries()->create(['url_image' => empty($uploadedFile['url']) ? $uploadedFile : $uploadedFile['url']]);
                }
            }
            if ($baseTask['group_tasks']) {
                $dataBaseTask->groupTasks()->attach($baseTask['group_tasks']);
            }
            $locations = Arr::get($data, 'task_locations');
            if ($locations) {
                foreach ($locations as $location) {
                    $idTaskLocation = $dataBaseTask->taskLocations()->create($location);
                    if ($location['task_location_jobs']) {
                        foreach ($location['task_location_jobs'] as $item) {
                            $idTaskLocation->taskLocationJob()->create($item);
                        }
                    }
                }
            }
            $events = Arr::get($data, 'events');
            if ($events) {
                foreach ($events as $event) {
                    $path = 'event/image/banner' . Carbon::now()->format('Ymd');
                    $event['banner_url'] = Storage::disk('s3')->putFileAs($path, $event['banner_url'], $event['banner_url']->hashName());
                    $idTaskEvent = $dataBaseTask->taskEvents()->create($event);
                    if ($event['details']) {
                        foreach ($event['details'] as $item) {
                            $idTaskEvent->eventDetails()->create($item);
                        }
                    }
                }
            }
            $generateNumber = $dataBaseTask->taskGenerateLinks()->createMany($this->generateNumber($dataBaseTask->slug));
            $socials = Arr::get($data, 'task_socials');
            if ($socials) {
                $dataBaseTask->taskSocials()->createMany($socials);
            }
            DB::commit();
        } catch (RuntimeException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new RuntimeException($exception->getMessage(), 500062, $exception->getMessage(), $exception);
        }

    }

    public function generateNumber($slug)
    {
        $type = [
            'facebook',
            'twitter',
            'telegram',
            'discord',
            'form',
        ];
        $dataLinkGenerate = [];
        foreach ($type as $key => $item) {
            $dataLinkGenerate[] = [
                'name' => 'Link share ' . $item,
                'type' => $key,
                'url' => config('app.link_event') . '/events/' . $slug . '?' . $item . '=' . Str::random(32),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ];
        }

        return $dataLinkGenerate;
    }

    public function findEvent($id)
    {
        return $this->repository->find($id);
    }

    function initData()
    {

        /*
         *
         * */
    }
}
