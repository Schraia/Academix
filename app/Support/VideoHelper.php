<?php

namespace App\Support;

class VideoHelper
{
    public static function youtubeId(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }
        $url = trim($url);
        if (preg_match('~(?:youtu\.be/|youtube\.com(?:/embed/|/shorts/|/v/|/watch\?v=))([\w-]{11})~', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public static function isUploadedVideoExtension(string $ext): bool
    {
        return in_array(strtolower($ext), ['mp4', 'webm', 'mov'], true);
    }
}
