<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalExamAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_choice_id',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(FinalExamAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(FinalExamQuestion::class, 'question_id');
    }

    public function choice()
    {
        return $this->belongsTo(FinalExamChoice::class, 'selected_choice_id');
    }
}
