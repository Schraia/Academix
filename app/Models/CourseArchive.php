<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseArchive extends Model
{
    protected $fillable = [
        'course_id',
        'label',
        'created_by',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function archivedLessons(): HasMany
    {
        return $this->hasMany(ArchivedLesson::class, 'course_archive_id')->orderBy('sort_order');
    }
}
