<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Bỏ đi ko dùng nữa 
class Withdraw extends Model
{
    use Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdraw_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['user_id', 'amount', 'address', 'status'];

    /**
     * @param $value
     *
     * @return int
     */
    public function getAmountAttribute($value)
    {
        return intval($value);
    }
}
