<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use App\Models\{User, TravelGame};
use App\Models\Event\{TaskEvent};

class UserCode extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'user_id',
        'task_event_id',
        'travel_game_id', // Travel game
        'number_code', // Mã nhận thưởng
        'color_code', // Mã màu,
        'status', // 0: unlock, 1: lock
        'is_prize', // 0: chưa đc giải, 1: đc giải
        'name_prize', // Tên giải
        'is_vip', // 0: normal, 1: vip
        'type', // 0: session, 1: booth
        'task_event_details_id' // session, booth
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskEvent()
    {
        return $this->belongsTo(TaskEvent::class);
    }

    public function travelGame()
    {
        return $this->belongsTo(TravelGame::class);
    }
}
