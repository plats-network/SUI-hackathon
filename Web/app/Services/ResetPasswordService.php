<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Repositories\ResetPasswordRepository;
use App\Services\Concerns\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService extends BaseService
{
    /**
     * @param ResetPasswordRepository $repository
     */
    public function __construct(ResetPasswordRepository $repository)
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
     * @param string $email
     * @param string $password New password
     */
    public function changePassword($userId, $password, $email = null)
    {
        if($userId) {
            $user = $this->find($userId);
        }
        if($email) {
            $user = $this->findByEmail($email);
        }

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

    /**
     * @param string $email
     * @param string $code
     *
     * @return mixed
     */
    public function getInfoResetPassword($email, $code)
    {
        return $this->repository->findByCode($email, $code);
    }
    
    /**
     * @param array $user
     *
     * @return mixed
     */
    public function updateOrCreate($user)
    {
        $data = [
            'code' => random_int(100000, 999999),
            'token' => Str::random(60)
        ];
        return $this->repository->updateOrCreate(
            ['email' => $user['email']],
            $data
        );
    }
}
