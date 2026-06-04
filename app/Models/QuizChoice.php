<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizChoice extends Model
{
    protected $table = 'quiz_choices';

    protected $fillable = [
        'id',
        'question_id',
        'choice_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
