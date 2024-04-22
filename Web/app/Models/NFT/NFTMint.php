<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NFTMint extends Model
{
    use SoftDeletes;

    protected $table = 'nft_mints';

    const ACTIVE = 1;
    const SENDING = 2;
    const ACCEPTED = 3;

    const TYPE_FREE = 1;

    protected $fillable = [
        'address_nft',
        'nft_title',
        'nft_symbol',
        'nft_uri',
        'secret_key',
        'seed',
        'private_organizer',
        'address_organizer',
        'type',
        'status',
        'metadata',
        'booth_id',
        'task_id',
        'session_id',
        'nft_category',
        'nft_res'
    ];
}

