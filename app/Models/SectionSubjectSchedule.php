<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionSubjectSchedule extends Model
{
    protected $table = 'section_subject_schedules';

    protected $fillable = [
        'college_section_id',
        'course_id',
        'time_slot',
        'days',
    ];

    public function collegeSection(): BelongsTo
    {
        return $this->belongsTo(CollegeSection::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
