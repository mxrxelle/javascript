<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['final_exam_id', 'question', 'order'];

    public function exam()
    {
        return $this->belongsTo(FinalExam::class, 'final_exam_id');
    }

    public function choices()
    {
        return $this->hasMany(FinalExamChoice::class, 'question_id');
    }
}
