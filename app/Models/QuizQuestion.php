<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    protected $fillable = [
        'id',
        'lesson_id',
        'question',
        'type',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function choices()
    {
        return $this->hasMany(QuizChoice::class, 'question_id');
    }
}
