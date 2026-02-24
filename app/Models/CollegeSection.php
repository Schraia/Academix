<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollegeSection extends Model
{
    protected $table = 'college_sections';

    protected $fillable = [
        'college_course_id',
        'year',
        'semester',
        'section_code',
        'time_slot',
        'sort_order',
    ];

    protected $casts = [
        'year' => 'integer',
        'semester' => 'integer',
        'sort_order' => 'integer',
    ];

    public function collegeCourse(): BelongsTo
    {
        return $this->belongsTo(CollegeCourse::class);
    }
}
