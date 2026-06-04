<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherCode extends Model
{
    use HasFactory;

    // Explicitly point to your actual database table schema
    protected $table = 'voucher_codes';

    // Mass assignable attributes matching your exact schema layout
    protected $fillable = [
        'course_id', 
        'code', 
        'claimed_by', 
        'claimed_at'
    ];

    // Dates that should be mutated to Carbon instances automatically
    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    // Link back to the Course model
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Link to the Student account who redeemed the system sequence
    public function student()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }
}