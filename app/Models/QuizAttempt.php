<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';

    protected $fillable = [
        'student_id',
        'lesson_id',
        'attempt_number',
        'score',
        'passed',
        'submitted_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizAttemptQuestion::class, 'attempt_id');
    }
}
