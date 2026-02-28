<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'banner_path',
        'block_section_id',
        'code',
        'credits',
        'status',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function blockSection()
    {
        return $this->belongsTo(BlockSection::class);
    }

    public function curriculum()
    {
        return $this->hasMany(Curriculum::class);
    }

    public function lessonModules()
    {
        return $this->hasMany(LessonModule::class, 'course_id')->orderBy('order');
    }

    public function discussionThreads()
    {
        return $this->hasMany(DiscussionThread::class, 'course_id');
    }

    public function courseGrades()
    {
        return $this->hasMany(CourseGrade::class, 'course_id');
    }

    public function courseGradeWeights()
    {
        return $this->hasMany(CourseGradeWeight::class, 'course_id');
    }

    public function courseAnnouncements()
    {
        return $this->hasMany(CourseAnnouncement::class, 'course_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_instructor');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}

