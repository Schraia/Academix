<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAttendance extends Model
{
    protected $table = 'course_attendance';

    protected $fillable = ['course_id', 'user_id', 'section_code', 'date', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
