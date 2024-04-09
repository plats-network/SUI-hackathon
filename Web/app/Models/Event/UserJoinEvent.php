<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event\TaskEvent;
use App\Models\Task;
use App\Models\Traits\Uuid;

class UserJoinEvent extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_join_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'user_id',
        'task_event_detail_id',
        'travel_game_id',
        'task_event_id',
        'task_id',
        'agent',
        'ip_address',
        'type', // 0: Session, 1: Booth
        'is_code', // false: chưa gen code, 1: gen code
        'is_important', // false or true
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    public function task_event()
    {
        return $this->belongsTo(TaskEvent::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
