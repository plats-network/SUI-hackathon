<?php

namespace App\Repositories;

use App\Models\ResetPassword;
use App\Repositories\Concerns\BaseRepository;

class ResetPasswordRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ResetPassword::class;
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
     * @param string $code
     *
     * @return mixed
     */
    public function findByCode($email, $code)
    {
        return $this->model->where('email', $email)->where('code', $code)->first();
    }
}
