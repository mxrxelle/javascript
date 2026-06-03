<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCode extends Model
{
    protected $fillable = [
        'course_id',
        'code',
        'is_used',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}