<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionThread extends Model
{
    use HasFactory;

    protected $table = 'discussions_threads';

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'course_id',
        'announcement_id',
        'status',
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function announcement()
    {
        return $this->belongsTo(CourseAnnouncement::class, 'announcement_id');
    }

    public function messages()
    {
        return $this->hasMany(DiscussionMessage::class, 'thread_id');
    }
}
