<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tab = $request->query('tab', 'all');

        $schoolYear = now()->year;
        $enrollmentCourseIds = $user->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->pluck('course_id');
        $assignedCourses = $user->isInstructor() ? $user->courses()->orderBy('title')->get() : collect();
        $tabCourses = $user->isAdmin()
            ? Course::orderBy('title')->limit(200)->get()
            : ($user->isInstructor() ? $assignedCourses : Course::whereIn('id', $enrollmentCourseIds)->orderBy('title')->get());

        $q = UserNotification::query()
            ->where('user_id', $user->id)
            ->with('course:id,title,code');

        if ($tab === 'discussions') {
            $q->where('kind', 'discussion');
        } elseif ($tab === 'starred') {
            $q->where('is_starred', true);
        } elseif ($tab !== 'all' && is_numeric($tab)) {
            $q->where('course_id', (int) $tab);
        }

        $notifications = $q->orderByDesc('created_at')->paginate(25)->withQueryString();

        $unreadCount = UserNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'tabCourses' => $tabCourses,
            'activeTab' => $tab,
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

    public function star(UserNotification $notification)
    {
        $user = Auth::user();
        abort_unless((int) $notification->user_id === (int) $user->id, 403);

        $notification->forceFill([
            'is_starred' => ! $notification->is_starred,
        ])->save();

        return response()->json([
            'starred' => (bool) $notification->is_starred,
        ]);
    }
}

