<?php

namespace App\Services\Admin;

use App\Repositories\RewardRepository;
use App\Services\Concerns\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardService extends BaseService
{

    /**
     * @param \App\Repositories\RewardRepository $rewardRepository
     */
    protected $repository;

    public function __construct(
         RewardRepository $rewardRepository)
    {
        $this->repository = $rewardRepository;
    }

    /**
     * Auto paginate with query parameters
     *
     * @param  array  $conditions
     *
     * @return mixed
     */

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
        if ($this->filter->has('description')) {
            $this->builder->where(function ($q) {
                $q->where('description', 'LIKE', '%' . $this->filter->get('description') . '%');
            });

            // Remove condition after apply query builder
            $this->cleanFilterBuilder('description');
        }
        if ($this->filter->has('status')) {
            $this->builder->where(function ($q) {
                $q->where('status', 'LIKE', '%' . $this->filter->get('status') . '%');
            });

            // Remove condition after apply query builder
            $this->cleanFilterBuilder('status');
        }

        return $this->endFilter();
    }

    public function store($request)
    {
        if (!$request->filled('id')) {
            return $this->create($request);
        }

        return $this->update($request);
    }

    public function create(Request $request)
    {
        $data = $request->except(['']);
        //Save cover
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $path = 'reward/image/' . Carbon::now()->format('Ymd');
            $data['image'] = Storage::disk('s3')->putFileAs($path, $uploadedFile, $uploadedFile->hashName());
        }
        $reward = $this->repository->create($data);

        return $reward;
    }

    public function update(Request $request)
    {
        $rewardId = $this->find($request->input('id'));

        $data = $request->except(['']);

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $path = 'reward/cover/' . Carbon::now()->format('Ymd');
            $data['image'] = Storage::disk('s3')->putFileAs($path, $uploadedFile, $uploadedFile->hashName());
        }
        $reward = $this->repository->updateByModel($rewardId, $data);
        return $reward;
    }
}
