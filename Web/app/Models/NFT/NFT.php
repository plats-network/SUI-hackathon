<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class NFT extends Model
{
    use SoftDeletes;

    protected $table = 'nft';

    //Network Type Phala
    const NETWORK_TYPE_PHALA = 'phala';
    //Network Type PHALA_ZERO
    const NETWORK_TYPE_PHALA_ZERO = 'aleph';
    //Astar
    const NETWORK_TYPE_ASTAR = 'astar';

    //polkadot
    const NETWORK_TYPE_POLKADOT = 'polkadot';

    //thêm 2 mạng aleph_testnet và astar_testnet
    const NETWORK_TYPE_ASTAR_TESTNET = 'astar_testnet';
    const NETWORK_TYPE_ALEPH_TESTNET = 'aleph_testnet';

    //Get all network name
    public static function getAllNetworkName()
    {
        return [
            self::NETWORK_TYPE_PHALA => 'Phala',
            //self::NETWORK_TYPE_PHALA_ZERO => 'Phala Zero',
            self::NETWORK_TYPE_ALEPH_TESTNET => 'Aleph Zero Testnet',
            self::NETWORK_TYPE_ASTAR => 'Astar',
            self::NETWORK_TYPE_POLKADOT => 'Polkadot',
            self::NETWORK_TYPE_ASTAR_TESTNET => 'Astar Testnet',
        ];
    }

    protected $fillable = [
        'task_id',
        'name',
        'description',
        'image_url',
        'permalink',
        'size',
        'blockchain',
        'asset_contract',
        'collection_id',
        'owner_id',
        'contract_date',
        'metadata'
    ];


    //Get Owner
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    //Get Collection
    public function collection()
    {
        return $this->belongsTo(NFTCollection::class);
    }

    //Get event_id  attribute from task_id
    public function getEventIdAttribute()
    {

        return $this->task_id;
    }



    //Create NFT Item
    // "nft" => array:4 [▼
    //    "name" => "23"
    //    "description" => "23"
    //    "size" => "23"
    //    "blockchain" => "3"
    //  ]
    public function createNFTItem($nft)
    {
        $nftItem = $this->create([
            'name' => $nft['name'],
            'description' => $nft['description'],
            'size' => $nft['size'],
            'blockchain' => $nft['blockchain'],
        ]);

        return $nftItem;
    }
    //url attribute
    public function getUrlAttribute()
    {
        return $this->permalink;
    }
}

