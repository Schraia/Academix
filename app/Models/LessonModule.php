<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonModule extends Model
{
    use HasFactory;

    protected $table = 'lessons_modules';

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'order',
        'type',
        'content',
        'video_url',
        'attachment_path',
        'attachment_original_name',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progressRecords()
    {
        return $this->hasMany(LessonProgress::class, 'lesson_module_id');
    }
}
