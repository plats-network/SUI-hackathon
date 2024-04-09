<?php

namespace App\Models\Event;

use App\Helpers\BaseImage;
use App\Models\Quiz\Quiz;
use App\Models\Event\UserJoinEvent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Task;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class TaskEvent extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'task_id',
        'name',
        'description',
        'banner_url',
        'type', // 0: Sesion, 1: Booth
        'max_job',
        'status',
        'code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
//        'id'
    ];

    public static function getTagClassName(): string
    {
        return Task::class;
    }

    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(self::getTagClassName(), 'taggable', 'taggables', null, 'tag_id')
            ->orderBy('order_column');
    }

    public function user_join_events()
    {
        return $this->hasMany(UserJoinEvent::class);
    }


    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function detail()
    {
        return $this->hasMany(TaskEventDetail::class)->orderBy('id', 'ASC');
    }


    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value) => BaseImage::loadImage($value)
        );
    }

}
