<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'category',
    ];

    public function codes()
    {
        return $this->hasMany(CourseCode::class);
    }

    public function students()
    {
        return $this->hasMany(StudentCourse::class);
    }
    
    public function modules()
    {
    return $this->hasMany(Module::class)
                ->orderBy('sort_order');
    }
}