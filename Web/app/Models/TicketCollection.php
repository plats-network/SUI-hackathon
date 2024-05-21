<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\Uuid;

class TicketCollection extends Model
{
    use Notifiable,Uuid;

    protected $table = 'ticket_collection';

    protected $fillable = [
        'id',
        'title',
        'description',
        'group',
        'amount',
        'available_amount',
        'task_id',
    ];
}

