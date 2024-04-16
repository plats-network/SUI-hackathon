<?php
// Không dùng nữa

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Bỏ đi ko dùng nữa
class TaskSocialResult extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_social_results';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'task_social_id',
        'status'
    ];
    
    /**
     * Get the task that owns the task social.
     */
    public function taskSocial()
    {
        return $this->belongsTo(TaskSocial::class);
    }
}
