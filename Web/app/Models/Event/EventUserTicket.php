<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Traits\Uuid;
use App\Models\User;
use Illuminate\Support\Str;

class EventUserTicket extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_user_tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'phone',
        'address',
        'email',
        'task_id',
        'user_id',
        'is_checkin',
        'hash_code',
        'is_vip',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    //Set checkin
    public function setCheckin($id)
    {
        $eventUserTicket = $this->find($id);
        $eventUserTicket->is_checkin = 1;
        $eventUserTicket->save();
    }

    //Generate checkin link
    public function generateCheckinLink($id)
    {
        $eventUserTicket = $this->find($id);
        $eventUserTicket->hash_code = Str::random(32);
        $eventUserTicket->save();
    }
    

    //người dùng đăng kí sự kiện
    public function user()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
