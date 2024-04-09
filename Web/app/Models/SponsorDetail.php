<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use App\Models\Sponsor;

class SponsorDetail extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sponsor_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'sponsor_id',
        'name',
        'description',
        'price',
    ];

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }
}
