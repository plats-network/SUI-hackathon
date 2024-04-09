<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Concerns\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
    
    /**
     * @param string $email
     *
     * @return mixed
     */
    public function findUserUnverify($email)
    {
        return $this->model->where('email', $email)->whereNull('email_verified_at')->first();
    }

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function findActiveUser($email)
    {
        return $this->model->where('email', $email)->whereNotNull('email_verified_at')->first();
    }
    
    /**
     * @param string $email
     * @param string $confirmCode
     *
     * @return mixed
     */
    public function findByConfirmCode($email, $confirmCode)
    {
        return $this->model->where('email', $email)->where('confirmation_code', $confirmCode)->first();
    }
}
