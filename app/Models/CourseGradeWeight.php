<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseGradeWeight extends Model
{
    protected $fillable = ['course_id', 'category', 'percentage'];

    protected $casts = [
        'percentage' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
