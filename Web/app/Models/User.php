<?php

namespace App\Models;

use App\Models\Event\UserJoinEvent;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Helpers\BaseImage;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Event\EventUserTicket;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'new_email',
        'birth',
        'gender',
        'avatar_path',
        'password',
        'role',
        'twitter',
        'facebook',
        'discord',
        'telegram',
        'email_verified_at',
        'confirmation_code',
        'comfirm_hash',
        'confirm_at',
        'status',
        'deleted_at',
        'wallet_address',
        'white_list',
        'event_object_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'check_avatar',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getAvatarPathAttribute()
    {
        return BaseImage::loadImage($this->attributes['avatar_path'] ?? null);
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return $this->attributesToArray();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function providers()
    {
        return $this->hasOne(Provider::class, 'user_id', 'id');
    }

    public function user_events()
    {
        return $this->hasMany(EventUserTicket::class, 'user_id', 'id');
    }

    /**
     * @return string
     */
    public function getBirthAttribute($birth)
    {
        if (is_null($this->attributes['birth'])) {
            return null;
        }

        return Carbon::parse($this->attributes['birth'])->format('d/m/Y');
    }

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    // $this->taskDone
    //                    ->whereUserId($user->id)
    //                    ->whereTaskEventDetailId($session->id)
    //                    ->count())
    //Count task done by user,
    //Param: $user, $session
    //Return: int
    public function taskDoneEvent($user_id, $session_id)
    {
        return UserJoinEvent::query()
            ->whereUserId($user_id)
            //->whereTaskEventDetailId($session_id)
            ->count();
    }
}
