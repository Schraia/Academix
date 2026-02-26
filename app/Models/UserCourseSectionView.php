<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourseSectionView extends Model
{
    protected $table = 'user_course_section_views';

    protected $fillable = [
        'user_id',
        'course_id',
        'announcements_seen_at',
        'lessons_seen_at',
        'grades_seen_at',
        'discussions_seen_at',
    ];

    protected $casts = [
        'announcements_seen_at' => 'datetime',
        'lessons_seen_at' => 'datetime',
        'grades_seen_at' => 'datetime',
        'discussions_seen_at' => 'datetime',
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
