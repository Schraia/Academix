<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'section_code',
        'name',
        'category',
        'score',
        'max_score',
        'graded_at',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'graded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
