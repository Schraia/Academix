<?php

namespace App\Services;

use App\Models\UserNotification;
use Illuminate\Support\Collection;

class UserNotifier
{
    /**
     * @param  iterable<int>  $userIds
     */
    public static function notifyMany(iterable $userIds, string $title, ?string $message, ?string $linkUrl, ?string $kind = null, ?int $courseId = null): void
    {
        $ids = Collection::wrap($userIds)->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return;
        }
        $now = now();
        $rows = $ids->map(fn (int $uid) => [
            'user_id' => $uid,
            'kind' => $kind,
            'course_id' => $courseId,
            'title' => $title,
            'message' => $message,
            'link_url' => $linkUrl,
            'read_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();
        UserNotification::insert($rows);
    }

    public static function notifyUser(int $userId, string $title, ?string $message, ?string $linkUrl, ?string $kind = null, ?int $courseId = null): void
    {
        UserNotification::create([
            'user_id' => $userId,
            'kind' => $kind,
            'course_id' => $courseId,
            'title' => $title,
            'message' => $message,
            'link_url' => $linkUrl,
        ]);
    }
}
