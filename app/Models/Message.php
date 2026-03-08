<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'course_id',
        'subject',
        'body',
        'send_individual',
    ];

    protected $casts = [
        'send_individual' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class);
    }

    public function recipientUsers()
    {
        return $this->belongsToMany(User::class, 'message_recipients', 'message_id', 'recipient_id')
                    ->withPivot('read_at', 'folder')
                    ->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }
}
