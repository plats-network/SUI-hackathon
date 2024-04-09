<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NFTSale extends Model
{
    use SoftDeletes;

    protected $table = 'nft_sales';

    protected $fillable = [
        'time',
        'asset_id',
        'collection_id',
        'auction_type',
        'contract_address',
        'quantity',
        'payment_symbol',
        'total_price',
        'seller_account',
        'from_account',
        'to_account',
        'winner_account',
    ];

    //Get Asset
    public function asset()
    {
        return $this->belongsTo(NFT::class);
    }

}

