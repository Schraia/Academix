<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionMessage extends Model
{
    use HasFactory;

    protected $table = 'discussions_messages';

    protected $fillable = [
        'content',
        'user_id',
        'thread_id',
        'parent_message_id',
        'is_edited',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(DiscussionThread::class, 'thread_id');
    }
}
