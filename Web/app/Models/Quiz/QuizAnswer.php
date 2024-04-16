<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;

class QuizAnswer extends Model
{
    use HasFactory, Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quiz_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'quiz_id',
        'name',
        'status', // true or false
    ];
    
    /**
     * Get the quiz result for the quiz.
     */
    public function quizResults()
    {
        return $this->hasMany(QuizResult::class, 'answer_id', 'id');
    }
}
