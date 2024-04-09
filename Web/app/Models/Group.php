<?php
// Không dùng nữa

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'name_en',
        'username',
        'country',
        'desc_vn',
        'desc_en',
        'avatar_url',
        'cover_url',
        'headline',
        'site_url',
        'twitter_url',
        'telegram_url',
        'facebook_url',
        'youtube_url',
        'discord_url',
        'instagram_url',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function user_groups()
    {
        return $this->hasMany(UserGroup::class);
    }
}
