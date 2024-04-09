<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNft extends Model
{
    use SoftDeletes;

    protected $table = 'user_nft';

    protected $fillable = [
        'user_id',
        'nft_mint_id',
        'type',
        'booth_id',
        'session_id',
        'task_id'
    ];
}

