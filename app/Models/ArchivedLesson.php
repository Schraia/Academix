<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivedLesson extends Model
{
    protected $fillable = [
        'course_archive_id',
        'title',
        'description',
        'content',
        'attachment_path',
        'attachment_original_name',
        'video_url',
        'sort_order',
        'source_lesson_id',
    ];

    public function courseArchive(): BelongsTo
    {
        return $this->belongsTo(CourseArchive::class, 'course_archive_id');
    }
}
