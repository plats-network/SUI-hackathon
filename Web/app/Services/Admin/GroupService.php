<?php 

namespace App\Services\Admin;

use App\Repositories\GroupRepository;
use App\Services\Concerns\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{DB, Storage};

class GroupService extends BaseService
{
    protected $repository;

    public function __construct(
        GroupRepository $groupRepository
    ) {
        $this->repository = $groupRepository;
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
        // $this->builder->with('taskRewards','taskLocation','taskSocial','taskGuides');
        return $this->endFilter();
    }

    public function store($request)
    {
        if ($request->has('id') && $request->filled('id')) {
            return $this->update($request);
        }
        
        return $this->create($request);
    }

    public function update($request)
    {
        DB::beginTransaction();

        try {
            $id = $request->get('id');
            $dataExcept = ['avatar', 'cover', 'id'];
            $group = $this->repository->find($id);

            if ($request->get('name') == $group->name) {
                $dataExcept = array_merge($dataExcept, ['name']);
            }

            if ($request->get('username') == $group->username) {
                $dataExcept = array_merge($dataExcept, ['username']);
            }

            $datas = $request->except($dataExcept);
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                $path = 'groups/avatar/' . Carbon::now()->format('Ymd');
                $datas['avatar_url'] = Storage::disk('s3')->putFileAs($path, $avatarFile, $avatarFile->hashName());
            }

            if ($request->hasFile('cover')) {
                $coverFile = $request->file('cover');
                $path = 'groups/cover/' . Carbon::now()->format('Ymd');
                $datas['cover_url'] = Storage::disk('s3')->putFileAs($path, $coverFile, $coverFile->hashName());
            }

            $datas['country'] = empty($request->get('country')) ?? 'global';

            // Save data
            $group->update($datas);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $datas = $request->except(['avatar', 'cover', 'id']);
            $datas['country'] = empty($request->get('country')) ?? 'global';

            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                $path = 'groups/avatar/' . Carbon::now()->format('Ymd');
                $datas['avatar_url'] = Storage::disk('s3')->putFileAs($path, $avatarFile, $avatarFile->hashName());
            }

            if ($request->hasFile('cover')) {
                $coverFile = $request->file('cover');
                $path = 'groups/cover/' . Carbon::now()->format('Ymd');
                $datas['cover_url'] = Storage::disk('s3')->putFileAs($path, $coverFile, $coverFile->hashName());
            }

            // Save data
            $this->repository->create($datas);

            DB::commit();
        } catch (RuntimeException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new RuntimeException($exception->getMessage(), 500062, $exception->getMessage(), $exception);
        }
    }
}
