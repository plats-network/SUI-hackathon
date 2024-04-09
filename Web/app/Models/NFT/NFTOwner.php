<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NFTOwner extends Model
{
    protected $table = 'nft_owners';

    protected $fillable = [
        'nft_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nft()
    {
        return $this->belongsTo(NFT::class);
    }
}
