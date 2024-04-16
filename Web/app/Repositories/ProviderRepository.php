<?php

namespace App\Repositories;

use App\Models\Provider;
use App\Repositories\Concerns\BaseRepository;

class ProviderRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Provider::class;
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
        return $this->model->where('email', $email)->firstOrFail();
    }
}
