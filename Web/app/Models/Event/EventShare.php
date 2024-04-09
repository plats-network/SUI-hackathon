<?php

namespace App\Models\Event;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventShare extends Model
{
    use HasFactory, Uuid;

    protected $table = 'event_share';

    protected $fillable = [
        'id',
        'task_id',
        'email',
        'name',
        'type'
    ];
}
