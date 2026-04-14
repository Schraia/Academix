<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseGrade;
use App\Models\CourseGradeWeight;
use App\Models\DiscussionMessage;
use App\Models\DiscussionThread;
use App\Models\Enrollment;
use App\Models\LessonModule;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ProfileController extends Controller
{
    public function show()
    {
        return $this->renderProfile(Auth::user());
    }

    public function showUser(User $user)
    {
        $viewer = Auth::user();
        if ($user->isAdmin() && ! $viewer->isAdmin()) {
            abort(403);
        }

        return $this->renderProfile($user);
    }

    protected function renderProfile(User $profileUser)
    {
        $viewer = Auth::user();
        $isOwnProfile = (int) $profileUser->id === (int) $viewer->id;
        $schoolYear = now()->year;

        $enrollmentIds = $profileUser->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->pluck('course_id');
        $enrolledCourses = Course::whereIn('id', $enrollmentIds)->get();

        $eligibleModuleQuery = LessonModule::query()
            ->whereIn('course_id', $enrollmentIds)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNotNull('attachment_path')->orWhereNotNull('video_url');
            });
        $eligibleModuleIds = (clone $eligibleModuleQuery)->pluck('id');

        $totalLessons = $eligibleModuleIds->count();
        $completedCount = LessonProgress::where('user_id', $profileUser->id)
            ->where('status', 'completed')
            ->whereIn('lesson_module_id', $eligibleModuleIds)
            ->count();
        $progressPercent = $totalLessons > 0 ? round($completedCount / $totalLessons * 100, 1) : 0;

        $showGrades = $isOwnProfile || $profileUser->grades_visible_on_profile;
        $gradesByCourse = [];
        $overallWeightedSum = 0;
        $coursesWithGrades = 0;
        if ($showGrades) {
            foreach ($enrolledCourses as $course) {
                $grades = CourseGrade::where('user_id', $profileUser->id)
                    ->where('course_id', $course->id)
                    ->where('is_visible', true)
                    ->whereNotNull('graded_at')
                    ->get();
                $weights = $course->courseGradeWeights()->get()->keyBy('category');
                $summary = $this->computeGradeSummary($grades, $weights);
                if ($summary['weighted_grade'] !== null) {
                    $gradesByCourse[] = [
                        'course' => $course,
                        'summary' => $summary,
                    ];
                    $overallWeightedSum += $summary['weighted_grade'];
                    $coursesWithGrades++;
                }
            }
        }
        $overallGradeAverage = $coursesWithGrades > 0 ? round($overallWeightedSum / $coursesWithGrades, 2) : null;

        $hiddenThreadIds = DB::table('user_hidden_profile_threads')->where('user_id', $profileUser->id)->pluck('thread_id');
        $threadIdsAuthored = DiscussionThread::where('user_id', $profileUser->id)->pluck('id');
        $threadIdsReplied = DiscussionMessage::where('user_id', $profileUser->id)->pluck('thread_id')->unique();
        $participatedThreadIds = $threadIdsAuthored->merge($threadIdsReplied)->unique()->values()->diff($hiddenThreadIds)->values();
        $discussionThreads = DiscussionThread::whereIn('id', $participatedThreadIds)
            ->with(['course:id,title', 'messages' => fn ($q) => $q->with('user:id,name')->latest()->limit(1)])
            ->orderByDesc('last_activity_at')
            ->limit(15)
            ->get();

        $tz = config('app.timezone', 'Asia/Manila');
        $completionRecords = LessonProgress::where('user_id', $profileUser->id)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get(['completed_at']);
        $countByWeek = [];
        foreach ($completionRecords as $record) {
            $week = $record->completed_at->setTimezone($tz)->format('o') . $record->completed_at->setTimezone($tz)->format('W');
            $countByWeek[$week] = ($countByWeek[$week] ?? 0) + 1;
        }
        $weeks = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subWeeks($i);
            $week = $date->format('o') . $date->format('W');
            $weeks[] = [
                'label' => $date->format('M j'),
                'count' => (int) ($countByWeek[$week] ?? 0),
            ];
        }
        $streak = $this->computeStreak($profileUser->id);

        return view('profile.show', [
            'user' => $profileUser,
            'isOwnProfile' => $isOwnProfile,
            'showGrades' => $showGrades,
            'progressPercent' => $progressPercent,
            'completedLessons' => $completedCount,
            'totalLessons' => $totalLessons,
            'gradesByCourse' => $gradesByCourse,
            'overallGradeAverage' => $overallGradeAverage,
            'discussionThreads' => $discussionThreads,
            'completionsByWeek' => $weeks,
            'maxCompletionsByWeek' => (int) max(1, collect($weeks)->max('count')),
            'streak' => $streak,
        ]);
    }

    public function diagnostics(?User $user = null)
    {
        $viewer = Auth::user();
        $user = $user ?: $viewer;
        if ($user->isAdmin() && ! $viewer->isAdmin()) {
            abort(403);
        }

        $byWeekday = array_fill_keys(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 0);
        $records = LessonProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get(['completed_at']);
        foreach ($records as $record) {
            $day = $record->completed_at->format('D');
            $byWeekday[$day] = ($byWeekday[$day] ?? 0) + 1;
        }

        $sessionsByWeekday = array_fill_keys(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 0);
        $sessionRows = DB::table('sessions')->where('user_id', $user->id)->get(['last_activity']);
        foreach ($sessionRows as $row) {
            $day = now()->setTimestamp((int) $row->last_activity)->format('D');
            $sessionsByWeekday[$day] = ($sessionsByWeekday[$day] ?? 0) + 1;
        }

        return view('profile.diagnostics', [
            'user' => $user,
            'isOwnProfile' => (int) $user->id === (int) $viewer->id,
            'completedByWeekday' => $byWeekday,
            'sessionsByWeekday' => $sessionsByWeekday,
        ]);
    }

    public function progressBreakdown(?User $user = null)
    {
        $viewer = Auth::user();
        $user = $user ?: $viewer;
        if ($user->isAdmin() && ! $viewer->isAdmin()) {
            abort(403);
        }
        $schoolYear = now()->year;
        $enrollmentIds = $user->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->pluck('course_id');
        $enrolledCourses = Course::whereIn('id', $enrollmentIds)->orderBy('title')->get();

        $breakdown = [];
        foreach ($enrolledCourses as $course) {
            $publishedModuleIds = LessonModule::where('course_id', $course->id)
                ->where('status', 'published')
                ->where(function ($q) {
                    $q->whereNotNull('attachment_path')->orWhereNotNull('video_url');
                })
                ->pluck('id');
            $total = $publishedModuleIds->count();
            $completed = LessonProgress::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereIn('lesson_module_id', $publishedModuleIds)
                ->count();
            $breakdown[] = [
                'course' => $course,
                'total' => $total,
                'completed' => $completed,
                'percent' => $total > 0 ? round($completed / $total * 100, 1) : 0,
            ];
        }

        return view('profile.progress', [
            'user' => $user,
            'breakdown' => $breakdown,
        ]);
    }

    /**
     * All enrollments grouped by year and semester, with units and grades
     */
    public function enrollmentsIndex()
    {
        $user = Auth::user();
        $enrollments = $user->enrollments()
            ->with(['course:id,title,code,credits', 'course.courseGradeWeights'])
            ->orderByDesc('enrolled_at')
            ->get();

        $grouped = [];
        foreach ($enrollments as $e) {
            $year = $e->enrolled_at->year;
            $parsed = $this->parseYearSemFromSectionName($e->section_name ?? '');
            $sem = $parsed ? (int) $parsed[1] : 0;
            $key = $year . '-' . $sem;
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'year' => $year,
                    'semester' => $sem,
                    'label' => $parsed
                        ? $year . ' — ' . $this->ordinal($parsed[0]) . ' Year, ' . $this->ordinal($parsed[1]) . ' Sem'
                        : $year . ' — ' . ($e->section_name ?: 'Enrolled'),
                    'items' => [],
                ];
            }
            $gradeSummary = null;
            if ($e->course) {
                $grades = CourseGrade::where('user_id', $user->id)
                    ->where('course_id', $e->course_id)
                    ->where('is_visible', true)
                    ->whereNotNull('graded_at')
                    ->get();
                $weights = $e->course->courseGradeWeights()->get()->keyBy('category');
                $gradeSummary = $this->computeGradeSummary($grades, $weights);
            }
            $units = $e->course ? (int) $e->course->credits : null;
            if (($units === null || $units === 0) && $this->isPpeEnrollment($e)) {
                $units = 2;
            }
            $grouped[$key]['items'][] = [
                'enrollment' => $e,
                'course' => $e->course,
                'units' => $units,
                'weighted_grade' => $gradeSummary['weighted_grade'] ?? null,
            ];
        }
        uasort($grouped, function ($a, $b) {
            if ($a['year'] !== $b['year']) {
                return $b['year'] <=> $a['year'];
            }
            return $b['semester'] <=> $a['semester'];
        });

        return view('profile.enrollments', [
            'user' => $user,
            'grouped' => array_values($grouped),
        ]);
    }

    private function parseYearSemFromSectionName(string $sectionName): ?array
    {
        if (preg_match('/(\d)(?:st|nd|rd|th)\s*Year\s*,\s*(\d)(?:st|nd|rd|th)\s*Semester/', $sectionName, $m)) {
            return [(int) $m[1], (int) $m[2]];
        }
        return null;
    }

    private function ordinal(int $n): string
    {
        $s = ['1st', '2nd', '3rd', '4th', '5th'];
        return $s[$n - 1] ?? (string) $n;
    }

    private function isPpeEnrollment(Enrollment $e): bool
    {
        $name = $e->course_name ?? '';
        if (str_starts_with($name, 'PPE') || $name === 'PPE') {
            return true;
        }
        if ($e->course && ($e->course->code && str_starts_with((string) $e->course->code, 'PPE'))) {
            return true;
        }
        return false;
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'bio' => 'nullable|string|max:2000',
            'profile_picture' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'gif', 'webp'])->max(2 * 1024)],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $path;
        }

        if ($request->has('bio')) {
            $user->bio = $request->input('bio');
        }
        if ($request->has('grades_visible_on_profile')) {
            $user->grades_visible_on_profile = $request->boolean('grades_visible_on_profile');
        }
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated.');
    }

    public function removePicture(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
            $user->profile_picture = null;
            $user->save();
        }
        return redirect()->route('profile.show')->with('success', 'Profile picture removed.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'nullable|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! empty($user->password)) {
            if (! Hash::check((string) $request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        $user->password = $request->new_password;
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function unfollowDiscussion(DiscussionThread $thread)
    {
        $user = Auth::user();
        DB::table('user_hidden_profile_threads')->insertOrIgnore([
            'user_id' => $user->id,
            'thread_id' => $thread->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('profile.show')->with('success', 'Discussion removed from your profile.');
    }

    private function computeGradeSummary($grades, $weights)
    {
        $byCategory = $grades->groupBy('category');
        $summary = [];
        $weightedSum = 0;
        $weightTotal = 0;
        foreach (['exam', 'quiz', 'activity', 'attendance'] as $cat) {
            $items = $byCategory->get($cat, collect());
            $avg = null;
            if ($items->isNotEmpty()) {
                $totalPct = $items->sum(fn ($g) => $g->max_score > 0 ? ($g->score ?? 0) / $g->max_score * 100 : 0);
                $avg = round($totalPct / $items->count(), 2);
            }
            $summary[$cat] = $avg;
            $w = $weights->get($cat);
            if ($w && $avg !== null) {
                $weightedSum += $avg * (float) $w->percentage / 100;
                $weightTotal += (float) $w->percentage;
            }
        }
        $weightedGrade = $weightTotal > 0 ? round($weightedSum, 2) : null;
        return ['by_category' => $summary, 'weighted_grade' => $weightedGrade, 'weights_defined' => $weights->isNotEmpty()];
    }

    private function computeStreak(int $userId): int
    {
        $tz = config('app.timezone', 'Asia/Manila');
        $dates = LessonProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->get()
            ->pluck('completed_at')
            ->map(fn ($d) => $d->setTimezone($tz)->format('Y-m-d'))
            ->unique()
            ->values();
        if ($dates->isEmpty()) {
            return 0;
        }
        $today = now($tz)->format('Y-m-d');
        if ($dates[0] !== $today && $dates[0] !== now($tz)->subDay()->format('Y-m-d')) {
            return 0;
        }
        $streak = 0;
        $check = now($tz)->format('Y-m-d');
        foreach ($dates as $d) {
            if ($d === $check) {
                $streak++;
                $check = date('Y-m-d', strtotime($check . ' -1 day'));
            } else {
                break;
            }
        }
        return $streak;
    }
}
