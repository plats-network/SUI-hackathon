<?php
// Không dùng nữa
namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskLocation extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'task_id',
        'reward_id',
        'name',
        'description',
        'amount',
        'job_num',
        'order',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return string
     */
    public function getCoordinateAttribute()
    {
        return $this->lat . ', ' . $this->long;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskLocationJobs()
    {
        return $this->hasMany(TaskLocationJob::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(TaskLocationHistory::class, 'location_id');
    }

    public function taskLocationJob()
    {
        return $this->hasMany(TaskLocationJob::class, 'task_location_id');
    }
    /**
     * Checkin guild
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guides()
    {
        return $this->hasMany(TaskLocationGuide::class, 'location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskUser()
    {
        return $this->belongsTo(TaskUser::class, 'task_id');
    }
}
