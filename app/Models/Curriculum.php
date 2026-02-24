<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Curriculum extends Model
{
    protected $table = 'curriculum';

    protected $fillable = [
        'college_course_id',
        'course_id',
        'year',
        'semester',
        'prerequisites',
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

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
