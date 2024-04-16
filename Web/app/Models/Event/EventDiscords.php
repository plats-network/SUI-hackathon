<?php

namespace App\Models\Event;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDiscords extends Model
{
    use HasFactory, Uuid;

    protected $table = 'event_discords';

    protected $fillable = [
        'id',
        'task_id',
        'bot_token',
        'channel_id',
        'channel_url',
        'status'
    ];
}
