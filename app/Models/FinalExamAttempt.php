<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'course_id', 
        'score', 
        'passed', 
        'correct_count', 
        'submitted_at'
    ];

    protected $casts = [
        'passed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function answers()
    {
        return $this->hasMany(FinalExamAttemptAnswer::class, 'attempt_id');
    }
}
