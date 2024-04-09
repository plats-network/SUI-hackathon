<?php
// Không dùng nữa

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Bỏ đi ko dùng nữa 
class TaskReward extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_rewards';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'task_id',
        'reward_id',
        'amount',
        'unit',
        'time_public',
        'time_campaign',
        'time_reward',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Get the task that owns the task_rewards.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the reward that owns the task_rewards.
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
