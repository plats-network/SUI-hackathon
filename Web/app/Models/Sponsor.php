<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use App\Models\{SponsorDetail, UserSponsor, Task};

class Sponsor extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sponsors';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'task_id',
        'name',
        'description',
        'price_type',
        'price',
        'end_at',
        'status',
    ];

    public function sponsorDetails()
    {
        return $this->hasMany(SponsorDetail::class);
    }

    public function userSponsors()
    {
        return $this->hasMany(UserSponsor::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
