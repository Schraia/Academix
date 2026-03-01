<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\LessonModule;
use App\Models\UserCourseSectionView;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Only students without current-year enrollments are sent to enroll page; instructor/admin skip
        if (! $user->isInstructor() && ! $user->isAdmin() && ! $user->hasCurrentYearEnrollments()) {
            return redirect()->route('enroll');
        }

        $schoolYear = now()->year;
        $nowUtc8 = Carbon::now('+08:00');
        $dayOfWeek = (int) $nowUtc8->format('w');

        $enrollments = $user->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->with('course')
            ->get();

        // Dashboard cards: students use enrollments; instructor uses assigned courses; admin uses all courses
        $dashboardCards = collect();
        if ($user->isInstructor()) {
            $assignedCourses = $user->courses()->orderBy('title')->get();
            $dashboardCards = $assignedCourses->map(fn ($c) => (object) [
                'course' => $c,
                'course_id' => $c->id,
                'enrollment' => null,
            ]);
        } elseif ($user->isAdmin()) {
            $allCourses = Course::orderBy('title')->get();
            $dashboardCards = $allCourses->map(fn ($c) => (object) [
                'course' => $c,
                'course_id' => $c->id,
                'enrollment' => null,
            ]);
        } else {
            $dashboardCards = $enrollments->map(fn ($e) => (object) [
                'course' => $e->course,
                'course_id' => $e->course_id,
                'enrollment' => $e,
            ]);
        }

        $courseIds = $dashboardCards->pluck('course_id')->filter()->unique()->values();

        // Per-card notification counts (announcements, lessons, grades, discussions)
        $views = [];
        if ($courseIds->isNotEmpty()) {
            $viewModels = UserCourseSectionView::where('user_id', $user->id)
                ->whereIn('course_id', $courseIds->toArray())
                ->get()
                ->keyBy('course_id');
            foreach ($courseIds as $cid) {
                $views[$cid] = $viewModels->get($cid);
            }
        }

        $cardBadges = [];
        foreach ($dashboardCards as $item) {
            $cid = $item->course_id;
            $c = $item->course;
            $view = $views[$cid] ?? null;

            $annQ = $c->courseAnnouncements();
            if (! $user->isInstructor() && ! $user->isAdmin()) {
                $annQ->where('is_visible', true);
            }
            $annCount = $view && $view->announcements_seen_at
                ? (clone $annQ)->where('created_at', '>', $view->announcements_seen_at)->count()
                : (clone $annQ)->count();

            $lessonQ = $c->lessonModules()->where('status', 'published');
            $lessonCount = $view && $view->lessons_seen_at
                ? (clone $lessonQ)->where('updated_at', '>', $view->lessons_seen_at)->count()
                : (clone $lessonQ)->count();

            $gradeQ = $c->courseGrades()->where('user_id', $user->id)->whereNotNull('graded_at');
            if (! $user->isInstructor() && ! $user->isAdmin()) {
                $gradeQ->where('is_visible', true);
            }
            $gradeCount = $view && $view->grades_seen_at
                ? (clone $gradeQ)->where('graded_at', '>', $view->grades_seen_at)->count()
                : (clone $gradeQ)->count();

            $discCount = $view && $view->discussions_seen_at
                ? $c->discussionThreads()->where('created_at', '>', $view->discussions_seen_at)->count()
                : $c->discussionThreads()->count();

            $cardBadges[$cid] = (object) [
                'announcements' => $annCount,
                'lessons' => $lessonCount,
                'grades' => $gradeCount,
                'discussions' => $discCount,
            ];
        }

        $todaysSchedules = $enrollments->filter(function ($e) use ($dayOfWeek) {
            return $this->enrollmentMatchesDayOfWeek($e->days ?? '', $dayOfWeek);
        })->map(function ($e) {
            $timeSlot = $e->time_slot ?? '';
            $courseName = $e->course_name ?? ($e->course?->title ?? '');
            $displayTitle = strlen($courseName) > 35 ? substr($courseName, 0, 32) . '...' : $courseName;
            $courseCode = $e->course?->code ?? '';
            return [
                'course_id' => $e->course_id,
                'time_slot' => $timeSlot,
                'course_name' => $courseName,
                'display_title' => $displayTitle,
                'course_code' => $courseCode,
            ];
        })->values();

        $todaysSchedules = $this->sortSchedulesByTime($todaysSchedules);

        $dateFormatted = $nowUtc8->format('l - m/d/Y');

        $announcements = collect();
        if ($courseIds->isNotEmpty()) {
            $announcements = CourseAnnouncement::whereIn('course_id', $courseIds)
                ->when(! $user->isInstructor() && ! $user->isAdmin(), fn ($q) => $q->where('is_visible', true))
                ->with('course')
                ->orderByDesc('created_at')
                ->limit(15)
                ->get();
        }

        $recentlyOpened = collect();
        if ($courseIds->isNotEmpty()) {
            $recentlyOpened = LessonModule::whereIn('course_id', $courseIds)
                ->whereNotNull('attachment_path')
                ->where('status', 'published')
                ->with('course')
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();
        }

        $profileRoleLabel = $user->isAdmin() ? 'Admin' : ($user->isInstructor() ? 'Instructor' : ($enrollments->first()?->section_name ?? 'Student'));

        return view('dashboard', [
            'todaysSchedules' => $todaysSchedules,
            'dateFormatted' => $dateFormatted,
            'enrollments' => $enrollments,
            'dashboardCards' => $dashboardCards,
            'cardBadges' => $cardBadges,
            'profileRoleLabel' => $profileRoleLabel,
            'announcements' => $announcements,
            'recentlyOpened' => $recentlyOpened,
        ]);
    }

    /**
     * Check if enrollment days string includes the given day of week.
     */
    protected function enrollmentMatchesDayOfWeek(?string $days, int $dayOfWeek): bool
    {
        if ($days === null || $days === '') {
            return false;
        }
        $days = strtoupper(trim($days));
        if ($dayOfWeek === 1) {
            return str_contains($days, 'M');
        }
        if ($dayOfWeek === 2) {
            return str_contains($days, 'TTH');
        }
        if ($dayOfWeek === 3) {
            return str_contains($days, 'W');
        }
        if ($dayOfWeek === 4) {
            return str_contains($days, 'TTH');
        }
        if ($dayOfWeek === 5) {
            return str_contains($days, 'F');
        }
        if ($dayOfWeek === 6) {
            return str_contains($days, 'S');
        }
        return false;
    }

    protected function sortSchedulesByTime($schedules)
    {
        return $schedules->sortBy(function ($item) {
            $slot = $item['time_slot'] ?? '';
            $parts = explode(' - ', $slot);
            $start = trim($parts[0] ?? '');
            return $this->timeToSortable($start);
        })->values();
    }

    protected function timeToSortable(string $time): int
    {
        if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i', trim($time), $m)) {
            $h = (int) $m[1];
            $min = (int) $m[2];
            if (strtoupper($m[3]) === 'PM' && $h !== 12) {
                $h += 12;
            }
            if (strtoupper($m[3]) === 'AM' && $h === 12) {
                $h = 0;
            }
            return $h * 60 + $min;
        }
        return 0;
    }
}
