<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Only students without current-year enrollments are sent to enroll page
        if (! $user->isInstructor() && ! $user->isAdmin() && ! $user->hasCurrentYearEnrollments()) {
            return redirect()->route('enroll');
        }

        $schoolYear = now()->year;
        $dayOfWeek = (int) date('w'); // 0=Sun, 1=Mon, ..., 6=Sat

        $enrollments = $user->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->get(['course_id', 'course_name', 'time_slot', 'days']);

        $todaysSchedules = $enrollments->filter(function ($e) use ($dayOfWeek) {
            return $this->enrollmentMatchesDayOfWeek($e->days ?? '', $dayOfWeek);
        })->map(function ($e) {
            $timeSlot = $e->time_slot ?? '';
            $courseName = $e->course_name ?? '';
            $displayTitle = strlen($courseName) > 35 ? substr($courseName, 0, 32) . '...' : $courseName;
            return [
                'course_id' => $e->course_id,
                'time_slot' => $timeSlot,
                'course_name' => $courseName,
                'display_title' => $displayTitle,
            ];
        })->values();

        $todaysSchedules = $this->sortSchedulesByTime($todaysSchedules);

        $dateFormatted = date('l - m/d/Y'); // e.g. "Tuesday - 02/24/2026"

        return view('dashboard', [
            'todaysSchedules' => $todaysSchedules,
            'dateFormatted' => $dateFormatted,
        ]);
    }

    /**
     * Check if enrollment days string includes the given day of week.
     * days: MW, TTH, F, S, etc. (0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat)
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

    /**
     * Sort schedule items by start time (time_slot like "8:00 AM - 9:30 AM").
     */
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

