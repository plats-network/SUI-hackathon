<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use App\Models\Event\TaskEvent;
use App\Models\TravelGame;

class MiniGame extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mini_games';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'task_event_id',
        'travel_game_id',
        'code',
        'type', // 0: session, 1: booth
        'is_vip', // Quay vip or normal
        'status', // Locked / Unlocked
        'banner_url', // Banner
        'type_prize', // Loại giải
        'num', // Số lượng giải
        'is_game', // Loại vòng quay
    ];

    public function taskEvent()
    {
        return $this->belongsTo(TaskEvent::class);
    }

    public function travelGame()
    {
        return $this->belongsTo(TravelGame::class);
    }
}
