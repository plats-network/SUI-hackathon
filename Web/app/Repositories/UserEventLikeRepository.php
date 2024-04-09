<?php

namespace App\Repositories;

use App\Models\Event\UserEventLike;
use App\Repositories\Concerns\BaseRepository;

class UserEventLikeRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserEventLike::class;
    }


}
