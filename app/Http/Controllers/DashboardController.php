<?php

namespace App\Http\Controllers;

use App\Models\CollegeSection;
use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\Enrollment;
use App\Models\LessonModule;
use App\Models\LessonProgress;
use App\Models\PendingEnrollment;
use App\Models\User;
use App\Models\UserCourseSectionView;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            // Per-user recently opened files (previews) — not global lesson updates
            $recentlyOpened = LessonModule::query()
                ->select('lessons_modules.*')
                ->join('lesson_progress as lp', 'lp.lesson_module_id', '=', 'lessons_modules.id')
                ->where('lp.user_id', $user->id)
                ->whereIn('lessons_modules.course_id', $courseIds)
                ->whereNotNull('lessons_modules.attachment_path')
                ->where('lessons_modules.status', 'published')
                ->with('course')
                ->orderByDesc('lp.updated_at')
                ->limit(10)
                ->get();
        }

        $profileRoleLabel = $user->isAdmin() ? 'Admin' : ($user->isInstructor() ? 'Instructor' : ($enrollments->first()?->section_name ?? 'Student'));

        $unreadNotificationsCount = UserNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        // If admin, gather metrics and render admin dashboard
        if ($user->isAdmin()) {
            $enrollmentStats = $this->getEnrollmentStats();
            $enrollmentTrend = $this->getEnrollmentTrend();
            $pendingStats = $this->getPendingStats();
            $workloadStats = $this->getWorkloadStats();
            $systemStats = $this->getSystemStats();
            $instructorWorkload = $this->getInstructorWorkload();
            $recentActivity = $this->getRecentActivity();
            $activityTotal = $this->getActivityTotal();

            return view('admin.admin-dashboard', [
                'enrollmentStats' => $enrollmentStats,
                'enrollmentTrend' => $enrollmentTrend,
                'pendingStats' => $pendingStats,
                'workloadStats' => $workloadStats,
                'systemStats' => $systemStats,
                'instructorWorkload' => $instructorWorkload,
                'recentActivity' => $recentActivity,
                'activityTotal' => $activityTotal,
            ]);
        }

        // Otherwise, render instructor/student dashboard
        return view('dashboard', [
            'todaysSchedules' => $todaysSchedules,
            'dateFormatted' => $dateFormatted,
            'enrollments' => $enrollments,
            'dashboardCards' => $dashboardCards,
            'cardBadges' => $cardBadges,
            'profileRoleLabel' => $profileRoleLabel,
            'announcements' => $announcements,
            'recentlyOpened' => $recentlyOpened,
            'unreadNotificationsCount' => $unreadNotificationsCount,
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

    /**
     * Get enrollment statistics for dashboard metrics.
     */
    protected function getEnrollmentStats(): array
    {
        $currentYear = now()->year;
        $total = Enrollment::whereYear('enrolled_at', $currentYear)
            ->where('status', 'enrolled')
            ->count();

        // Calculate trend: compare this week vs last week
        $thisWeekStart = now()->startOfWeek();
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = $lastWeekStart->copy()->endOfWeek();

        $thisWeekCount = Enrollment::whereBetween('enrolled_at', [$thisWeekStart, now()])
            ->where('status', 'enrolled')
            ->count();

        $lastWeekCount = Enrollment::whereBetween('enrolled_at', [$lastWeekStart, $lastWeekEnd])
            ->where('status', 'enrolled')
            ->count();

        $trend = $lastWeekCount > 0 ? round((($thisWeekCount - $lastWeekCount) / $lastWeekCount) * 100) : 0;

        return [
            'total' => $total,
            'trend' => $trend,
        ];
    }

    /**
     * Get enrollment trend for the last 7 days.
     */
    protected function getEnrollmentTrend(): array
    {
        $end = now()->endOfDay();
        $start = now()->subDays(6)->startOfDay();
        $counts = Enrollment::query()
            ->selectRaw('DATE(enrolled_at) as day, COUNT(*) as total')
            ->whereBetween('enrolled_at', [$start, $end])
            ->where('status', 'enrolled')
            ->groupBy(DB::raw('DATE(enrolled_at)'))
            ->pluck('total', 'day');

        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->toDateString();
            $trend[] = [
                'label' => $date->format('D'),
                'count' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $trend;
    }

    /**
     * Get pending enrollment statistics.
     */
    protected function getPendingStats(): array
    {
        $pending = PendingEnrollment::where('status', 'pending')->count();
        $approved = PendingEnrollment::where('status', 'approved')->count();
        $rejected = PendingEnrollment::where('status', 'rejected')->count();
        $total = $pending + $approved + $rejected;

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
        ];
    }

    /**
     * Get instructor workload statistics.
     */
    protected function getWorkloadStats(): array
    {
        $instructorCourses = User::where('role', 'instructor')
            ->withCount('courses')
            ->get();

        $max = $instructorCourses->max('courses_count') ?? 0;
        $avg = $instructorCourses->avg('courses_count') ?? 0;

        return [
            'average' => round($avg, 1),
            'max' => $max,
        ];
    }

    /**
     * Get system statistics (user counts, courses, sections).
     */
    protected function getSystemStats(): array
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalCourses = Course::count();
        $totalSections = CollegeSection::count();

        return [
            'totalStudents' => $totalStudents,
            'totalInstructors' => $totalInstructors,
            'totalUsers' => $totalStudents + $totalInstructors + User::where('role', 'admin')->count(),
            'totalCourses' => $totalCourses,
            'totalSections' => $totalSections,
        ];
    }

    /**
     * Get instructor workload distribution.
     */
    protected function getInstructorWorkload(): array
    {
        return User::where('role', 'instructor')
            ->with('courses')
            ->get()
            ->map(function ($instructor) {
                return [
                    'id' => $instructor->id,
                    'name' => $instructor->name ?: $instructor->email,
                    'courseCount' => $instructor->courses->count(),
                ];
            })
            ->sortByDesc('courseCount')
            ->values()
            ->toArray();
    }

    /**
     * Get recent activity log (latest enrollments/approvals).
     */
    protected function getRecentActivity(): array
    {
        // Get recent enrollments
        $recentEnrollments = Enrollment::with('user')
            ->latest('enrolled_at')
            ->limit(15)
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => 'enroll-' . $enrollment->id,
                    'email' => $enrollment->user?->email ?? '-',
                    'action' => 'Enrolled',
                    'status' => 'Enrolled',
                    'statusClass' => 'enrolled',
                    'timestamp' => $enrollment->enrolled_at->format('M d, Y - g:i A'),
                    'ts' => $enrollment->enrolled_at?->timestamp ?? 0,
                ];
            });

        // Get recent pending enrollment updates
        $recentPending = PendingEnrollment::with('user')
            ->latest('updated_at')
            ->limit(15)
            ->get()
            ->map(function ($pending) {
                $status = $pending->status;
                $statusClass = $status === 'approved' ? 'approved' : ($status === 'rejected' ? 'rejected' : 'pending');

                return [
                    'id' => 'pending-' . $pending->id,
                    'email' => $pending->user?->email ?? '-',
                    'action' => ucfirst($status) . ' Enrollment',
                    'status' => ucfirst($status),
                    'statusClass' => $statusClass,
                    'timestamp' => ($pending->updated_at ?? $pending->created_at)->format('M d, Y - g:i A'),
                    'ts' => ($pending->updated_at ?? $pending->created_at)?->timestamp ?? 0,
                ];
            });

        // Merge and sort by timestamp
        $activities = $recentEnrollments->merge($recentPending)
            ->sortByDesc('ts')
            ->values()
            ->slice(0, 20)
            ->map(function ($activity) {
                unset($activity['ts']);
                return $activity;
            })
            ->toArray();

        return $activities;
    }

    /**
     * Get total activity count for display.
     */
    protected function getActivityTotal(): int
    {
        return Enrollment::count() + PendingEnrollment::count();
    }
}
