<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollegeOptionSchedule extends Model
{
    protected $fillable = [
        'college_course_id',
        'year',
        'semester',
        'option_type',
        'option_code',
        'option_label',
        'course_code',
        'time_slot',
        'days',
        'student_slots',
        'sort_order',
    ];

    protected $casts = [
        'year' => 'integer',
        'semester' => 'integer',
        'student_slots' => 'integer',
        'sort_order' => 'integer',
    ];

    public function collegeCourse(): BelongsTo
    {
        return $this->belongsTo(CollegeCourse::class);
    }
}
