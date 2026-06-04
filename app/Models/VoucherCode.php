<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherCode extends Model
{
    protected $fillable = [
        'course_id',
        'code',
        'claimed_by',
        'claimed_at'
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }
}
