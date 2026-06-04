<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'type',
        'content',
        'youtube_url',
        'sort_order',
        'presentation_path',
        'presentation_size',
        'quiz_questions_count',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'lesson_id');
    }

    public function progress()
    {
        return $this->hasMany(StudentLessonProgress::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function files()
    {
        return $this->hasMany(LessonFile::class);
    }
}