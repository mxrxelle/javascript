<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptQuestion extends Model
{
    protected $table = 'quiz_attempt_questions';

    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_choice_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function choice()
    {
        return $this->belongsTo(QuizChoice::class, 'selected_choice_id');
    }
}
