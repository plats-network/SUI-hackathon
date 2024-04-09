<?php

namespace App\Repositories;

use App\Models\Withdraw;
use App\Repositories\Concerns\BaseRepository;

class WithdrawRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Withdraw::class;
    }
}
