<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TicketNftMint extends Model
{
    use Notifiable;

    protected $table = 'ticket_nft_mint';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'address_nft',
        'ticket_id',
        'created_at',
        'digest',
        'txt_hash'
    ];
}

