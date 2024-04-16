<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;
use App\Models\User;

class QuizResult extends Model
{

    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quiz_results';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'task_id',
        'user_id',
        'point',
        'answer_id'
    ];

    /**
     * Get the task_guides for the tasks.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
