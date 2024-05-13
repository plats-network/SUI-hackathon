<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TaskEventDetailNftMint extends Model
{
    use Notifiable;

    protected $table = 'task_event_detail_nft_mint';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'address_nft',
        'task_event_detail_id',
        'created_at',
        'task_id',
        'txt_hash'
    ];
}

