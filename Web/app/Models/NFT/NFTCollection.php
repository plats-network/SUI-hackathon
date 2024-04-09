<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;

class NFTCollection extends Model
{
    protected $table = 'nft_collections';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'url',
    ];
}

