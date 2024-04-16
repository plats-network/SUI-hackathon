<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Bỏ đi ko dùng nữa 
class UserTaskReward extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_task_rewards';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'detail_reward_id',
        'type', // 0: Tokens, 1: NFTs, 2: Vouchers, 3: card mobile
        'amount', // số lượng token, ntfs...
        'is_consumed', // false: chưa sử dụng, true: Đã xử dụng
        'consume_at', // Ngày sử dụng
        'is_open', // false: chưa mở, true: Đã mở
        'is_tray', // false: locktray, true: maintray
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'deleted_at',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function detail_reward()
    {
        return $this->belongsTo(Branch::class);
    }
}
