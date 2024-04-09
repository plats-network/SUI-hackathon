<?php

namespace App\Models\Event;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSocial extends Model
{
    use HasFactory, Uuid;

    protected $table = 'event_social';

    protected $fillable = [
        'id',
        'task_id',
        'url',
        'text',
        'is_comment',
        'is_like',
        'is_retweet',
        'is_tweet',
        'type',
    ];
}
