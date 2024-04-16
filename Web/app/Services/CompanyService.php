<?php

namespace App\Services;

use App\Repositories\CompanyRepository;
use App\Services\Concerns\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyService extends BaseService
{
    /**
     * @param \App\Repositories\CompanyRepository $companyRepository
     */
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

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

        if (isset($conditions['withCount'])) {
            foreach ($conditions['withCount'] as $relation) {
                $this->builder = $this->builder->withCount($relation);
            }

            $this->cleanFilterBuilder('withCount');
        }

        return $this->endFilter();
    }


    /**
     * Create or Update the task
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed|void
     * @throws \Prettus\Validator\Exceptions\ValidatorException|\Prettus\Repository\Exceptions\RepositoryException
     */
    public function store($request)
    {
        if (!$request->filled('id')) {
            return $this->create($request);
        }

        return $this->update($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(Request $request)
    {
        $data['name'] = $request->get('name');
        $data['address'] = $request->get('address');
        $data['phone'] = $request->get('phone');
        // $data['phone'] = $request->user()->id;

        //Save cover
        if ($request->hasFile('logo_path')) {
            $uploadedFile = $request->file('logo_path');
            $path = 'companies/icons/' . Carbon::now()->format('Ymd');
            $data['logo_path'] = Storage::disk('s3')->putFileAs($path, $uploadedFile, $uploadedFile->hashName());
        }

        $company = $this->companyRepository->create($data);

        $this->withSuccess(trans('admin.task_created'));

        return $company;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update(Request $request)
    {
        $task = $this->find($request->input('id'));

        $data = $request->except(['image', 'locations', 'guilds']);

        // $uploadedFile = $request->file('image');
        // $path = 'tasks/cover/' . Carbon::now()->format('Ymd');
        // dd(Storage::disk('s3')->put($path, $uploadedFile, $uploadedFile->hashName()));
        // $data['image'] = Storage::disk('s3')->putFileAs($path, $uploadedFile, $uploadedFile->hashName());
        //Save cover
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $path = 'tasks/cover/' . Carbon::now()->format('Ymd');
            // dd(Storage::disk('s3')->putFileAs($path, $uploadedFile, $uploadedFile->hashName()));
            $data['image'] = Storage::disk('s3')->putFileAs($path, $uploadedFile, $uploadedFile->hashName());
        }

        $task = $this->repository->updateByModel($task, $data);

        //Update/Create Location
        if ($request->filled('locations')) {
            $createLocations = [];
            $updateLocations = [];
            foreach ($request->input('locations', []) as $location) {
                if (isset($location['id']) && !empty($location['id'])) {
                    $updateLocations[] = $location;
                    continue;
                }

                $createLocations[] = $location;
            }

            //Create location
            $this->createLocation($task, $createLocations);
            $this->updateLocation($task, $updateLocations);
        }

        //Delete location
        if ($request->filled('location_delete')) {
            $task->locations()->whereIn('id', $request->input('location_delete', []))->delete();
        }

        return $task;
    }
}
