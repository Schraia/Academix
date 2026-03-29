<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingEnrollmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pending_enrollment_id',
        'college_course_id',
        'course_name',
        'section_name',
        'section_code',
        'time_slot',
        'days',
        'units',
    ];

    public function pendingEnrollment()
    {
        return $this->belongsTo(PendingEnrollment::class);
    }
}

