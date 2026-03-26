<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = UserNotification::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(25);

        $unreadCount = UserNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function go(Request $request, UserNotification $notification)
    {
        $user = Auth::user();
        abort_unless((int) $notification->user_id === (int) $user->id, 403);

        if ($notification->read_at === null) {
            $notification->forceFill(['read_at' => now()])->save();
        }

        $target = $notification->link_url ?: route('notifications.index');

        // Avoid open redirects: allow relative links only.
        if (is_string($target) && str_starts_with($target, '/')) {
            return redirect($target);
        }

        return redirect()->route('notifications.index');
    }
}

