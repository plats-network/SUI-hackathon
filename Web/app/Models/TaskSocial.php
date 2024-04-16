<?php
// Không dùng nữa

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TweetEnum;

class TaskSocial extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_socials';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'task_id',
        'reward_id',
        'name',
        'description',
        'platform', // Twitter, Fb, Youtube, Telegram, Discord
        'type', // Like, Share...
        'url',
        'amount',
        'order',
        'lock',
        'status',
    ];

    /**
     * Get the task that owns the task social.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
