<?php

namespace App\Services;

use App\Repositories\ProviderRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProviderService extends BaseService
{
    /**
     * @param \App\Repositories\ProviderRepository $repository
     */
    public function __construct(ProviderRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function index($idUser){
        return $this->repository->findById($idUser);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(Request $request)
    {
        $data             = $request->toArray();
        $data['password'] = Hash::make($request->input('password'));
        $data['role']     = USER_ROLE;

        return $this->repository->create($data);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update(Request $request)
    {
        $user = $this->find($request->input('id'));

        return $this->repository->updateByModel($user, $request->only('name'));
    }

    /**
     * User change password
     *
     * @param string $userId
     * @param string $password New password
     */
    public function changePassword($userId, $password)
    {
        $user = $this->find($userId);

        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->repository->findByEmail($email);
    }
}
